<?php
declare(strict_types=1);
require '../../../vendor/autoload.php';

class ScreenMeasurement {
   
    public function __construct
    (
        public float $diagonal,
        public int $aspectRatioWidth,
        public int $aspectRatioHeight,
        public int $pixelResolutionWidth,
        public int $pixelResolutionHeight,
        public int $dpResolution = 0,
        public int $densityBucket = 0,
        public int $dimension = 0,
        public float $inchWidth = 0,
        public float $inchHeight = 0,
        public int $logicDpWidth = 0,
        public int $logicDpHeight = 0
     ) {}
    /**
     * Функция возвращает разрешение экрана в дюймах
     * @return array
     */
    public function findScreenDimensions(): array {
        $pow = pow(($this->aspectRatioWidth % $this->aspectRatioHeight), 2);
        $this->inchWidth = $this->diagonal / (sqrt($pow) + 1);
        $this->inchHeight = (($this->aspectRatioWidth / $this->aspectRatioHeight) * $this->inchWidth);
        $this->inchWidth = round($this->inchWidth, 2, PHP_ROUND_HALF_UP);
        $this->inchHeight = round($this->inchHeight, 2, PHP_ROUND_HALF_UP);
        return [
            $this->inchWidth, $this->inchHeight  
        ];
    }
    /**
     * Функция находит (DPI) устройства
     * @return int
     */
    public function computeDpResolution(): int 
    {
        $powOfWidthAndHeight = pow($this->pixelResolutionWidth, 2) + pow($this->pixelResolutionHeight, 2);
        $sqrtOfPows = sqrt($powOfWidthAndHeight);
        $result = $sqrtOfPows / $this->diagonal;
        $result = round($result, 0, PHP_ROUND_HALF_UP);
        $this->dpResolution = (int)$result;
        return $this->dpResolution;
    }

    /**
     * Функция возвращает примерное логическое разрашение экрана
     * @param int $dpResolution
     * @return array
     */
    public function findDpResoulution(int $dpResolution): array {
        $notDp = $this->dpResolution / 160;
        $this->logicDpWidth = (int)($this->pixelResolutionWidth / $notDp);
        // $this->logicDpWidth = (int)$this->logicDpWidth;
        $this->logicDpHeight = (int)($this->pixelResolutionHeight / $notDp);
        // $this->logicDpHeight = (int)$this->logicDpHeight;
        return [
            $this->logicDpWidth,
            $this->logicDpHeight
        ];
    }
    public function findDensityBucket(int $dpResolution): array {
        $densityBuckets = [
            "ldpi" => 120,
            "mdpi" => 160,
            "hdpi" => 240,
            "xhdpi" => 320,
            "xxhdpi" => 480,
            "xxxhdpi" => 640,
        ];

        foreach ($densityBuckets as $key => $value) {
            if ($this->dpResolution <= $value) {
                return [$key => $this->dpResolution];
            }
        }
        return [];
    }
    

}

// $t = (new ScreenMeasurement(
//     readline(),
//     readline(),
//     readline(),
//     readline(),
//     readline())
//   )->findScreenDimensions();
// print_r($t);

$measurementOfPhone = new ScreenMeasurement(
    (int)readline("Введите диагональ экрана: "),
    (int)readline("Введите соотношение стороны 1: "),
    (int)readline("Введите соотношение стороны 2: "),
    (int)readline("Введите ширину экрана: "),
    pixelResolutionHeight: (int)readline("Введите высоту экрана: ")
)
  ;

print_r($measurementOfPhone->findDpResoulution($measurementOfPhone->computeDpResolution()));
print_r($measurementOfPhone->findScreenDimensions());
print_r($measurementOfPhone->findDensityBucket($measurementOfPhone->computeDpResolution()));
