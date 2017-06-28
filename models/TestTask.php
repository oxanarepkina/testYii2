<?php
namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;


class TestTask extends Model
{
    public $file;
    public $filename;
    public $result;

    public function rules(){
        return[
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'txt']
        ];
    }

    // upload file and save it into a project directory
    public function uploadFile(UploadedFile $uploadedFile)
    {
        $this->file = $uploadedFile;

        if($this->validate())
        {
            return $this->saveFile();
        }
    }

    private function getFolder()
    {
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename()
    {
        return strtolower(md5(uniqid($this->file->baseName)) . '.' . $this->file->extension);
    }

    private function saveFile()
    {
        $this->filename = $this->generateFilename();

        $this->file->saveAs($this->getFolder() . $this->filename);

        return $this->filename;
    }

    // Work with uploaded file
    private function readFileAsArray()
    {
        return file($this->getFolder() . $this->filename);
    }

    /**
     * Filter input array and return array of lines that contain only numbers
     * @param $data_list_array
     * array of all file content including lines with symbols
     * @return array
     * of lines that contain only numbers
     */
    private function fetchNumericLines($data_list_array)
    {
        $filtered_num_arr = [];
        foreach ($data_list_array as $line_number => $line_content) {
            if (!preg_match("/[a-zA-Zа-яА-Я]+/u", $line_content)) {
                $filtered_num_arr[$line_number] = $line_content;
            }
        }
       return $filtered_num_arr;
    }

    /**
     * @param $num_arr
     * filtered array that contains only numbers
     * @return array
     * that contains number of the line => result sum of all numbers in this line
     */
    private function calculateSumOfEachLine($num_arr)
    {
        $sum_result_arr = [];
        foreach ($num_arr as $key => $numbers_line) {
            $numbers_arr = explode(" ", $numbers_line);
            $line_sum = 0;
            foreach ($numbers_arr as $number) {
                $line_sum += intval($number);
            }
            $sum_result_arr[$key] = $line_sum;
        }
       return $sum_result_arr;
    }

    /**
     * @param $array
     * of sum of each line
     * @return mixed
     * sorted array in a descending order
     */
    private function sortArray($array){
        arsort($array);
        return $array;
    }

    /**
     * read data from file as array,
     * filter this data into numeric array,
     * calculate sum of each line in array,
     * sort array in a descending order
     * @return mixed
     */
    public function getResult(){
        $data_list_array = $this->readFileAsArray();
        $num_arr = $this->fetchNumericLines($data_list_array);

        return $this->sortArray($this->calculateSumOfEachLine($num_arr));
    }
}