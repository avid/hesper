<?php
/**
 * Created by PhpStorm.
 * User: byorty
 * Date: 18.07.16
 * Time: 14:51
 */

namespace Hesper\Main\UI\View;

use Hesper\Core\Base\Stringable;
use Hesper\Core\Base\Timestamp;
use Hesper\Core\Exception\WrongArgumentException;
use Hesper\Main\Flow\Model;

class CsvView implements View, Stringable {

    const EOL = "\n";

    protected $download = null;
    protected $excelStrings = false;
    protected $encoding = 'cp1251';

    /**
     * @return CsvView
     **/
    public static function create() {
        return new self;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setDownload($filename) {
        $this->download = $filename;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getDownload() {
        return $this->download;
    }

    /**
     * @param bool $enable
     * @return $this
     */
    public function setExcelStrings($enable) {
        $this->excelStrings = (bool)$enable;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExcelStrings() {
        return $this->excelStrings == true;
    }

    /**
     * @param string $encoding
     * @return $this
     */
    public function setEncoding($encoding) {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @return string
     */
    public function getEncoding() {
        return $this->encoding;
    }

    /**
     * @param Model $model
     * @return $this
     */
    public function render(Model $model = null) {
        $csv = $this->toString($model);

        /** @var Model $model */
        if ($this->download) {
            if (is_string($this->download)) {
                $filename = $this->download;
            } else {
                $filename = 'csv_' . Timestamp::makeNow()->toFormatString('Y.m.d_H.i.s') . '.csv';
            }
            header('Content-Type: application/octet-stream; charset=' . $this->encoding);
            header('Content-Length: ' . strlen($csv));
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
        } else {
            header('Content-Type: text/plain; charset=' . ($this->encoding ?: 'utf-8'));
        }
        echo $csv;
        return $this;
    }

    /**
     * @param Model $model
     * @return string
     * @throws WrongArgumentException
     */
    public function toString(Model $model = null) {
        if ($model == null) {
            throw new WrongArgumentException('$model is required');
        }
        if (!$model->has('data')) {
            throw new WrongArgumentException('"data" in model is not defined');
        }

        $csv = '';
        if ($model->has('fields')) {
            $csv .= self::rowToCsv($model->get('fields'), $this->excelStrings) . self::EOL;
        }

        foreach ($model->get('data') as $row) {
            $csv .= self::rowToCsv($row, $this->excelStrings) . self::EOL;
        }

        if ($this->encoding && strtoupper($this->encoding) != 'UTF-8') {
            $csv = iconv('UTF-8', $this->encoding . '//TRANSLIT', $csv);
        }

        return $csv;
    }

    /**
     * @param array $row
     * @param bool $isExcel
     * @return string
     */
    public static function rowToCsv(array $row, $isExcel = false) {
        if ($isExcel) {
            $exporter = function ($value) {
                if (is_int($value)) {
                    return $value;
                }
                if (is_float($value)) {
                    return str_replace('.', ',', (string)$value);
                }
                if (is_string($value)) {
                    $value = htmlspecialchars_decode($value);
                    if (strpos($value, '"') !== false) {
                        return '"' . str_replace('"', '""', $value) . '"';
                    }
                }
                return '"=""' . $value . '"""';
            };
        } else {
            $exporter = function ($value) {
                if (is_int($value))
                    return $value;
                return '"' . $value . '"';
            };
        }

        return implode(';', array_map($exporter, $row));
    }

}