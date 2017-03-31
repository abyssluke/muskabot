<?php
//This script was written by  id:hope-echoes
//http://d.hatena.ne.jp/hope-echoes/20080426/1209141189 {{{

// Mutex �Ɏg����N���X
interface Exclusive
{
    /**
     * ���b�N���擾����B
     *
     * locking() �� false �Ȃ�Ώ�Ɏ��s����B
     *
     * @return bool
     * @see locking()
     */
    public function lock();

    /**
     * ���b�N����Ă��邩�ǂ����B
     *
     * �Ⴆ�������g�����b�N���Ă��Ă� true �B
     * �����A locking() �� true �Ȃ�� locked() �� true �B
     *
     * @return bool
     * @see locking()
     */
    public function locked();

    /**
     * ���̃C���X�^���X���g�����b�N���Ă��邩�ǂ����B
     *
     * @return bool
     */
    public function locking();

    /**
     * �A�����b�N����B
     *
     * locking() �� false �Ȃ�Ώ�Ɏ��s����B
     *
     * @return bool
     * @see locking()
     */
    public function unlock();
}

/**
 * flock() ���g�����t�@�C���x�[�X�� Mutex �����B
 *
 * �g����
 *
 * $mutex = new MutexFile('/path/to/file.lock');
 *
 * if ($mutex->lock()) {
 *     // ���b�N���擾�ł���܂ő҂B
 * }
 *
 * if ($mutex->locked()) {
 *     // ���b�N����Ă����ꍇ�̏����B
 * }
 *
 * ���ӓ_
 *
 * 1. �}���`�X���b�h�͑S���l�����Ă��Ȃ��B
 *    (Windows/Apache/PHP ���͒��ӂ��邱��)
 * 2. ���b�N�Ɏg���t�@�C�����A�ʂ̏ꏊ�ŊJ���Ȃ����ƁB
 *    �����ɂ���ẮA����v���Z�X���ŕʁX�ɊJ���ꂽ����̃t�@�C���́A
 *    ���b�N��������L���Ă���ׁB
 *    �Ⴆ�΁A����̃t�@�C����ʁX�ɊJ�� (A, B �Ƃ���) �A A �Ƀ��b�N
 *    ������� B �����ƁA A �̃��b�N����������Ă��܂��B
 * 3. �f�X�g���N�^�Ŏ����I�Ƀ��b�N�����������B
 * 4. �g�p���ꂽ�t�@�C���͎����I�ɂ͍폜����Ȃ��B
 *    ���̃v���Z�X���g���Ă��邩������Ȃ��ׁB
 * 5. ���b�N�Ɏg�����t�@�C���ɑ΂��Ă̓ǂݏ����͍l�����Ă��Ȃ��B
 *    ���b�N��p�Ɗ���؂��t�@�C�����w�肷�邱�ƁB
 * 6. ���X�� @ ���g���Ă���̂͂����̎�Ȃ̂ŋC�ɂ��Ȃ����ƁB
 *    (�t�@�C������̓G���[���o���₷�����������ǁA
 *     �N���肤��G���[�����O�ɑS�ă`�F�b�N����͈̂ٗl�ɖʓ|)
 *
 * @see Exclusive
 * @see flock()
 */
class MutexFile implements Exclusive
{
    // {{{ PUBLIC

    public function __construct($filepath)
    {
        // {{{ �G���[�����B$filepath �͓ǂݏ����ł���p�X�ł���K�v������B

        if (!is_string($filepath) || !$filepath) {
            // �p�X����Ȃ��B
            throw new InvalidArgumentException(
                'arg#1[string:!empty] File path.');
        } elseif (file_exists($filepath)) {
            if (!is_file($filepath)) {
                // �p�X���t�@�C������Ȃ��B
                throw new InvalidArgumentException(
                    "$filepath is not a file.");
            } elseif (!is_readable($filepath) || !is_writable($filepath)) {
                // �p�X�ɏ������߂Ȃ��B
                throw new InvalidArgumentException(
                    "$filepath is not readable/writable.");
            }
        } elseif (!@touch($filepath)) {
            // �p�X�ɏ������߂Ȃ��B
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

        // - �G���[���������܂� }}}

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

    // {{{ Exclusive ����

    /**
     * ����v���Z�X���̑��̃C���X�^���X�����b�N���擾���Ă���ꍇ�A
     * (�҂K�v���Ȃ��̂�) �����Ɏ��s����B
     * �f�b�h���b�N���������ׁA 5 �b�Ń��b�N���擾�ł��Ȃ�������
     * ���s����B
     * ��蒷���^�C���A�E�g��ݒ肵�����Ȃ�� test() ���g�����ƁB
     *
     * @see Exclusive::lock()
     * @see test()
     */
    public function lock()
    {
        return $this->test(5, 1);
    }

    /**
     * ���ۂ� flock() �ł��邩�����Ă݂�B
     * flock() �ł����ꍇ�̓��b�N�������Ă����B
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

    // - Exclusive ���� }}}
    // {{{ �Ǝ����\�b�h

    /**
     * �^�C���A�E�g��ݒ肵�ă��b�N�����s����B
     *
     * ����v���Z�X���̑��̃C���X�^���X�����b�N���擾���Ă���ꍇ�A
     * �����Ɏ��s����B
     * �^�C���A�E�g���ǂ��ݒ肵�Ă��A���Ȃ��Ƃ� 1 ��͎��s����B
     *
     * @param int $timeout �^�C���A�E�g�b���B�f�t�H���g�ł� 0 �B
     * @param int $interval ���s�Ԋu�̕b���B�f�t�H���g�ł� 0 �B
     * @param int &$count ���ۂɎ��s�����񐔁B
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

    // - �Ǝ����\�b�h }}}
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