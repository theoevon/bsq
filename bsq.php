<?php

function cloneBoard($array, $rowLength)
{
    $newArr = array();
    $rowLength += 1;
    // echo "row Length in cloneBoard: $rowLength\n";
    foreach ($array as $row) {
        for ($i = 0; $i < $rowLength; $i++) {
            if ($row[$i] == "." ){
                $row[$i] = '1';
            } else {
                $row[$i] = '0';
            }
        }
        $newArr[] = $row;
    }
    return $newArr;
}

function check($cell, $topLeft = 0, $top = 0, $left = 0)
{
    // echo "Min from [$topLeft, $top, $left] : ".min([$topLeft, $top, $left])."\n";
    // echo "\$cell == $cell, check returns ".($cell == 0 ? 0 : min([$topLeft, $top, $left]) + 1).PHP_EOL;
    return $cell == 0 ? 0 : min([$topLeft, $top, $left]) + 1;
}

function maxSquare($array, $rowLength)
{
    $rowIndex = 0;
    $bigSquarePos = array();
    $x = null;
    $y = null;
    $maxNum = 0;

    $valHeight = count($array);
    $val = array_fill(0, $valHeight, array_fill(0, $rowLength+1, 0));
    // var_dump($array, $val);

    foreach ($array as $y => $row) {
        foreach (str_split($row) as $x => $char) {
            $val[$y][$x] = @intval($char);
            // echo "FILLED - $x/$y = $char\n";
        }
    }


    // print_r($val);

    foreach ($val as $key => $row) {
        // var_dump("ISSOU", $row);
        // print_r($val[$rowIndex][1]);
        for ($i = 0; $i < $rowLength+1; $i++) {
            // echo "\n";
            
            $topLeft = isset($val[$rowIndex - 1][$i - 1]) ? $val[$rowIndex - 1][$i - 1] : 0;
            $top = isset($val[$rowIndex - 1][$i]) ? $val[$rowIndex - 1][$i] : 0;
            $left = isset($val[$rowIndex][$i - 1]) ? $val[$rowIndex][$i - 1] : 0;
            
            $cellValue = @intval($val[$i][$rowIndex]);

            // echo "check cell input: "
            $check = check($val[$rowIndex][$i], $topLeft, $top, $left);
            // var_dump($check);
            // echo "$i/$key - $cellValue -> [$topLeft, $top, $left] = $check\n";
            $val[$rowIndex][$i] = $check;
            
            if ($check > $maxNum) {
                // echo "New biggest: $check at [$i, $rowIndex]\n";
                $maxNum = $check;
                $x = $i;
                $y = $rowIndex;
            }
        }
        $rowIndex++; 
    }
    return ['size' => $maxNum, "x" => $x, "y" => $y];
}

function displayBigSquare($board, $data)
{
    $size = $data['size'];
    $x = $data['x'];
    $y = $data['y'];
    
    for ($i = 0; $i < $size; $i++) { 
        for ($j = 0; $j < $size; $j++) { 
            $board[$y - $i][$x - $j] = 'x'; 
        }
    }

    foreach ($board as $row) {
        echo "$row\n";
    }

}


function bsq($file)
{
    $numLines;
    $board = [];

    $handle = fopen($file, 'r');
    if ($handle){
        $lines = fgets($handle);
        $numLines = intval($lines);
        $i = 0;
        while ($line = fgets($handle)){
            $board[$i] = trim($line);
            $i++;
        }
        $rowLength = strlen($board[0]) - 1;
        $board2 = cloneBoard($board, $rowLength);
        
        $data = maxSquare($board2, $rowLength);
        
        displayBigSquare($board, $data);
    }
    fclose($handle);
}

bsq($argv[1]);