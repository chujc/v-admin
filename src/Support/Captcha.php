<?php

namespace ChuJC\Admin\Support;


use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Captcha
{
    private $config;
    private $files;
    // 验证码图片实例
    private $im = null;
    // 验证码字体颜色
    private $color = null;
    // 验证码
    private $text = null;

    // 验证码字符集合
    protected $codeSet = '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY';
    // 验证码过期时间（s）
    protected $expire = 1800;
    // 验证码字体大小(px)
    protected $fontSize = 25;
    // 是否画混淆曲线
    protected $useCurve = true;
    // 是否添加杂点
    protected $useNoise = true;
    // 验证码图片高度
    protected $imageH = 0;
    // 验证码图片宽度
    protected $imageW = 0;
    // 验证码位数
    protected $length = 5;
    // 背景颜色
    protected $bg = [243, 251, 254];
    // 算术验证码
    protected $math = false;
    // 字体文件夹
    protected $fontsDirectory;
    // 字体库
    protected $fonts;
    // 字体
    protected $font;

    public function __construct(Repository $config, Filesystem $files)
    {
        $this->config = $config;
        $this->files = $files;
        $this->fontsDirectory = config('admin.captcha.fontsDirectory', __DIR__ . '/assets/fonts');
    }

    /**
     * @param array $config
     * @return void
     */
    protected function configure($config)
    {
        if (is_null($config)) {
            $config = $this->config->get('admin.captcha', []);
        }

        foreach ($config as $key => $val) {
            if (property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    /**
     * 创建验证码
     * @return array
     * @throws \Exception
     */
    protected function generate(): array
    {
        $bag = '';

        if ($this->math) {
            $x = random_int(10, 30);
            $y = random_int(1, 9);
            $bag = "$x+$y=";
            $key = $x + $y;
            $key .= '';
        } else {
            $characters = str_split($this->codeSet);
            for ($i = 0; $i < $this->length; $i++) {
                $bag .= $characters[rand(0, count($characters) - 1)];
            }
            $key = mb_strtolower($bag, 'UTF-8');
        }

        $hash = password_hash($key, PASSWORD_BCRYPT, ['cost' => 10]);

        $uuid = (string)Str::uuid();
        Cache::put("captcha.{$uuid}", $hash, Carbon::now()->addSeconds($this->expire));

        $this->text = str_split($bag);

        return [
            'uuid' => $uuid
        ];
    }

    /**
     * 验证验证码是否正确
     * @access public
     * @param string $code 用户验证码
     * @param string $uuid
     * @return bool 用户验证码是否正确
     */
    public function check(string $code, string $uuid): bool
    {
        if (!Cache::has("captcha.{$uuid}")) {
            return false;
        }

        $key = Cache::get("captcha.{$uuid}");

        $code = mb_strtolower($code, 'UTF-8');

        $res = password_verify($code, $key);

        if ($res) {
            Cache::forget("captcha.{$uuid}");
        }

        return $res;
    }

    /**
     * 输出验证码并把验证码的值保存的cache中
     * @param array|null $config
     * @return array
     * @throws \Exception
     */
    public function create(array $config = null)
    {
        $this->configure($config);
        // 图片宽(px)
        $this->imageW || $this->imageW = $this->length * $this->fontSize * 1.5 + $this->length * $this->fontSize / 2;
        // 图片高(px)
        $this->imageH || $this->imageH = $this->fontSize * 2.5;
        // 建立一幅 $this->imageW x $this->imageH 的图像
        $this->im = imagecreate($this->imageW, $this->imageH);
        // 设置背景
        imagecolorallocate($this->im, $this->bg[0], $this->bg[1], $this->bg[2]);
        // 验证码字体随机颜色
        $this->color = imagecolorallocate($this->im, mt_rand(1, 150), mt_rand(1, 150), mt_rand(1, 150));

        $this->fonts = $this->files->files($this->fontsDirectory ?: __DIR__ . '/assets/fonts');

        $this->fonts = array_map(function ($file) use (&$findFonts) {
            if ($this->font && $this->font === $file->getFileName()) {
                return $file->getPathName();
            }
            return $file->getPathName();
        }, $this->fonts);
        $this->fonts = array_values($this->fonts); //reset fonts array index

        if ($this->useNoise) {
            $this->writeNoise();
        }
        if ($this->useCurve) {
            $this->writeCurve();
        }


        $generator = $this->generate();
        foreach ($this->text as $index => $char) {
            $x = $this->fontSize * ($index + 1) * mt_rand(1.2, 1.6) * ($this->math ? 1 : 1.5);
            $y = $this->fontSize + mt_rand(10, 20);
            $angle = $this->math ? 0 : mt_rand(-40, 40);

            imagettftext($this->im, $this->fontSize, $angle, $x, $y, $this->color, $this->font(), $char);
        }

        ob_start();
        // 输出图像
        imagepng($this->im);
        $content = ob_get_clean();
        imagedestroy($this->im);

        $generator['image'] = base64_encode($content);
        return $generator;
    }

    /**
     * 画一条由两条连在一起构成的随机正弦函数曲线作干扰线(你可以改成更帅的曲线函数)
     *
     *      高中的数学公式咋都忘了涅，写出来
     *        正弦型函数解析式：y=Asin(ωx+φ)+b
     *      各常数值对函数图像的影响：
     *        A：决定峰值（即纵向拉伸压缩的倍数）
     *        b：表示波形在Y轴的位置关系或纵向移动距离（上加下减）
     *        φ：决定波形与X轴位置关系或横向移动距离（左加右减）
     *        ω：决定周期（最小正周期T=2π/∣ω∣）
     *
     */
    protected function writeCurve(): void
    {
        $px = $py = 0;
        // 曲线前部分
        $A = mt_rand(1, $this->imageH / 2); // 振幅
        $b = mt_rand(-$this->imageH / 4, $this->imageH / 4); // Y轴方向偏移量
        $f = mt_rand(-$this->imageH / 4, $this->imageH / 4); // X轴方向偏移量
        $T = mt_rand($this->imageH, $this->imageW * 2); // 周期
        $w = (2 * M_PI) / $T;

        $px1 = 0; // 曲线横坐标起始位置
        $px2 = mt_rand($this->imageW / 2, $this->imageW * 0.8); // 曲线横坐标结束位置

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->imageH / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->fontSize / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, $px + $i, $py + $i, $this->color); // 这里(while)循环画像素点比imagettftext和imagestring用字体大小一次画出（不用这while循环）性能要好很多
                    $i--;
                }
            }
        }

        // 曲线后部分
        $A = mt_rand(1, $this->imageH / 2); // 振幅
        $f = mt_rand(-$this->imageH / 4, $this->imageH / 4); // X轴方向偏移量
        $T = mt_rand($this->imageH, $this->imageW * 2); // 周期
        $w = (2 * M_PI) / $T;
        $b = $py - $A * sin($w * $px + $f) - $this->imageH / 2;
        $px1 = $px2;
        $px2 = $this->imageW;

        for ($px = $px1; $px <= $px2; $px = $px + 1) {
            if (0 != $w) {
                $py = $A * sin($w * $px + $f) + $b + $this->imageH / 2; // y = Asin(ωx+φ) + b
                $i = (int)($this->fontSize / 5);
                while ($i > 0) {
                    imagesetpixel($this->im, $px + $i, $py + $i, $this->color);
                    $i--;
                }
            }
        }
    }

    /**
     * 随机添加线条与字母或字符数字
     * @author john_chu
     */
    protected function writeNoise(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $color = imagecolorallocate($this->im, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200));
            imageline($this->im, mt_rand(0, $this->imageW), mt_rand(0, $this->imageH), mt_rand(0, $this->imageW), mt_rand(0, $this->imageH), $color);
        }
        $codeSet = '0123456789abcdefhijkmnpqrstuvwxyz~!@#$%^&*()_+|-={}[]:;",<.>/?';
        for ($i = 0; $i < 20; $i++) {
            $color = imagecolorallocate($this->im, mt_rand(180, 255), mt_rand(180, 255), mt_rand(180, 255));
            imagestring($this->im, mt_rand(10, 30), mt_rand(0, $this->imageW), mt_rand(0, $this->imageH), $codeSet[mt_rand(0, 61)], $color);
        }
    }

    /**
     * Image fonts
     *
     * @return string
     */
    protected function font(): string
    {
        return $this->fonts[rand(0, count($this->fonts) - 1)];
    }
}
