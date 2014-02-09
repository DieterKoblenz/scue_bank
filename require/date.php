<?php


Function date_math($SourceDate,$doMath,$ReturnFmt){
        /*
         * ~~ jm AT trinitywebdev DOT com ~~
         *
         * doMath Format[+/-][Qty][Unit]   
         *
         * Example +6m = Add 6 Months
         * Example -180d = Subtract 180 Days
         * Example +1Y = Add 1 Year
         *
         * Unit follows date() formats
         * @return newday
         */
        $SourceDate = strtotime($SourceDate);
        if(!$SourceDate){
            return(0);
        }
        if($ReturnFmt == ''){
            $ReturnFmt = 'm/d/Y';
        }
        $m = date('m',$SourceDate);
        $d = date('d',$SourceDate);
        $Y = date('Y',$SourceDate);
        $MathFunc = substr($doMath,0,1);
        $Unit = substr($doMath,-1,1);
        $Qty = substr($doMath, 1,strlen($doMath)-2);
        if($MathFunc == '-'){
            Switch($Unit){
                Case "m":
                    $newday = mktime(0,0,0,($m-$Qty),$d,$Y);
                break;
                Case "d":
                    $newday = mktime(0,0,0,$m,($d-$Qty),$Y);
                break;
                Case "Y":
                    $newday = mktime(0,0,0,$m,$d,($Y-$Qty));
                break;
            }
           
        }elseif($MathFunc == '+'){

            Switch($Unit){
                Case "m":
                    $newday = mktime(0,0,0,($m+$Qty),$d,$Y);
                break;
                Case "d":
                    $newday = mktime(0,0,0,$m,($d+$Qty),$Y);
                break;
                Case "Y":
                    $newday = mktime(0,0,0,$m,$d,($Y+$Qty));
                break;
            }
        }elseif($doMath ==''){
            $newday = mktime(0,0,0,$m,$d,$Y);
        }
       
        $newday = date($ReturnFmt,$newday);
        return($newday);
    }
?>