<?php
//This script was written by  id:hope-echoes
//http://d.hatena.ne.jp/hope-echoes/20080426/1209141189 {{{

// Mutex に使えるクラス
interface Exclusive
{
    /**
     * ロックを取得する。
     *
     * locking() が false ならば常に失敗する。
     *
     * @return bool
     * @see locking()
     */
    public function lock();

    /**
     * ロックされているかどうか。
     *
     * 例え自分自身がロックしていても true 。
     * 即ち、 locking() が true ならば locked() も true 。
     *
     * @return bool
     * @see locking()
     */
    public function locked();

    /**
     * このインスタンス自身がロックしているかどうか。
     *
     * @return bool
     */
    public function locking();

    /**
     * アンロックする。
     *
     * locking() が false ならば常に失敗する。
     *
     * @return bool
     * @see locking()
     */
    public function unlock();
}

/**
 * flock() を使ったファイルベースの Mutex 実装。
 *
 * 使い方
 *
 * $mutex = new MutexFile('/path/to/file.lock');
 *
 * if ($mutex->lock()) {
 *     // ロックが取得できるまで待つ。
 * }
 *
 * if ($mutex->locked()) {
 *     // ロックされていた場合の処理。
 * }
 *
 * 注意点
 *
 * 1. マルチスレッドは全く考慮していない。
 *    (Windows/Apache/PHP 等は注意すること)
 * 2. ロックに使うファイルを、別の場所で開かないこと。
 *    実装によっては、同一プロセス中で別々に開かれた同一のファイルは、
 *    ロック制御を共有している為。
 *    例えば、同一のファイルを別々に開き (A, B とする) 、 A にロック
 *    した後で B を閉じると、 A のロックが解除されてしまう。
 * 3. デストラクタで自動的にロックが解除される。
 * 4. 使用されたファイルは自動的には削除されない。
 *    他のプロセスが使っているかもしれない為。
 * 5. ロックに使ったファイルに対しての読み書きは考慮していない。
 *    ロック専用と割り切れるファイルを指定すること。
 * 6. 所々で @ を使っているのはただの趣味なので気にしないこと。
 *    (ファイル周りはエラーを出しやすい部分だけど、
 *     起こりうるエラーを事前に全てチェックするのは異様に面倒)
 *
 * @see Exclusive
 * @see flock()
 */
class MutexFile implements Exclusive
{
    // {{{ PUBLIC

    public function __construct($filepath)
    {
        // {{{ エラー処理。$filepath は読み書きできるパスである必要がある。

        if (!is_string($filepath) || !$filepath) {
            // パスじゃない。
            throw new InvalidArgumentException(
                'arg#1[string:!empty] File path.');
        } elseif (file_exists($filepath)) {
            if (!is_file($filepath)) {
                // パスがファイルじゃない。
                throw new InvalidArgumentException(
                    "$filepath is not a file.");
            } elseif (!is_readable($filepath) || !is_writable($filepath)) {
                // パスに書き込めない。
                throw new InvalidArgumentException(
                    "$filepath is not readable/writable.");
            }
        } elseif (!@touch($filepath)) {
            // パスに書き込めない。
            throw new InvalidArgumentException(
                "$filepath is not writable.");
        }

        if (!isset(self::$locker[$filepath])) {
            self::$locker[$filepath] = array(
                'count' => 0,
                'current' => 0,
                'pointer' => @fopen($filepath, 'rb+'));
            if (!self::$locker[$filepath]['pointer']) {
                throw new UnexpectedValueException;
            }
        }

        // - エラー処理ここまで }}}

        self::$locker[$filepath]['count']++;
        $this->id = ++self::$instances;
        $this->path = $filepath;
    }

    public function __destruct()
    {
        $this->unlock();
        if (!--self::$locker[$this->filepath]['count']) {
            @fclose(self::$locker[$this->filepath]['pointer']);
            unset(self::$locker[$this->filepath]);
        }
    }

    // {{{ Exclusive 実装

    /**
     * 同一プロセス中の他のインスタンスがロックを取得している場合、
     * (待つ必要もないので) 即座に失敗する。
     * デッドロックを回避する為、 5 秒でロックが取得できなかったら
     * 失敗する。
     * より長いタイムアウトを設定したいならば test() を使うこと。
     *
     * @see Exclusive::lock()
     * @see test()
     */
    public function lock()
    {
        return $this->test(5, 1);
    }

    /**
     * 実際に flock() できるか試してみる。
     * flock() できた場合はロック解除しておく。
     *
     * @see Exclusive::locked()
     */
    public function locked()
    {
        return !($this->test() && $this->unlock());
    }

    // @see Exclusive::locking()
    public function locking()
    {
        return self::$locker[$this->path]['current'] == $this->id;
    }

    // @see Exclusive::unlock()
    public function unlock()
    {
        if (!$this->locking() ||
            !@flock(self::$locker[$this->path]['pointer'], LOCK_UN)) {
            return false;
        }

        self::$locker[$this->path]['current'] = 0;
        return true;
    }

    // - Exclusive 実装 }}}
    // {{{ 独自メソッド

    /**
     * タイムアウトを設定してロックを試行する。
     *
     * 同一プロセス中の他のインスタンスがロックを取得している場合、
     * 即座に失敗する。
     * タイムアウトをどう設定しても、少なくとも 1 回は試行する。
     *
     * @param int $timeout タイムアウト秒数。デフォルトでは 0 。
     * @param int $interval 試行間隔の秒数。デフォルトでは 0 。
     * @param int &$count 実際に試行した回数。
     * @return bool
     * @see flock()
     */
    public function test($timeout = 0, $interval = 0, &$count = 0)
    {
        if (!is_int($timeout) && !ctype_digit($timeout) || $timeout < 0) {
            throw new InvalidArgumentException(
                'arg#1[int:!negative] Timeout in seconds.');
        } elseif (!is_int($interval) && !ctype_digit($interval) ||
                  $interval < 0) {
            throw new InvalidArgumentException(
                'arg#2[int:!negative] Challenge interval in seconds.');
        } elseif (self::$locker[$this->path]['current']) {
            return false;
        }

        $time_to = time() + $timeout - $interval;
        $count = 0;
        do {
            $count++;
            if (@flock(self::$locker[$this->path]['pointer'],
                       LOCK_EX | LOCK_NB)) {
                self::$locker[$this->path]['current'] = $this->id;
                return true;
            }
        } while ((time() < $time_to) && (sleep($interval) || true));

        return false;
    }

    // - 独自メソッド }}}
    // - PUBLIC }}}
    // {{{ PROTECTED

    protected static $instances = 0;
    protected static $locker = array();

    protected $id = 0;
    protected $path = '';

    // - PROTECTED }}}
}

// }}}

?>