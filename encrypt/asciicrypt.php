<?php
Class ASCIICrypt{ 
    private $arrClave; 
    private $modo; 
    private $mensaje; 

    function __construct($a,$b,$c) { 
        $this->arrClave=$this->dameClave($a); 
        $this->modo=$b; 
        $this->mensaje=$this->dameMensaje($c); 
    } 

    private function dameClave($str){ 
        $clave=stripslashes($str); 
        for($i=0;$i<strlen($clave);$i++){ 
            $arrClave[$i]=md5($clave[$i]); 
        } 
        return $arrClave; 
    } 
    
    private function dameMensaje($str){ 
        $mensaje=stripslashes($str); 
        if($this->modo=="en"){//encriptar 
            $asciiMensaje=$this->dameAsciisYLong($mensaje); 
        }else{ 
            $asciiMensaje=$this->sustLetrasXNums($mensaje); 
        } 
        return $asciiMensaje; 
    } 

    public function efectua(){ 
        $asciiMensaje=$this->mensaje; 
    
        for($nc=0;$nc<count($this->arrClave);$nc++){ 
            $numRecorridoClaveInterna=-1; 
            $asciiClave=$this->dameAsciis($this->arrClave[$nc]); 
            
            for($i=0;$i<strlen($asciiMensaje);$i++){ 
                $numHash=$this->dameNumClaveInterna(&$numRecorridoClaveInterna,$asciiClave[$nc]); 
                
                if($this->modo=="en"){//encriptar 
                    if($this->divisiblePor3($numHash)==true){ 
                        $sumaTemp=$this->suma($asciiMensaje[$i],$numHash); 
                    }else{ 
                        $sumaTemp=$this->resta($asciiMensaje[$i],$numHash); 
                    } echo $str; 
                }else{ 
                    if($this->divisiblePor3($numHash)==true){ 
                        $sumaTemp=$this->resta($asciiMensaje[$i],$numHash); 
                    }else{ 
                        $sumaTemp=$this->suma($asciiMensaje[$i],$numHash); 
                    } 
                } 
                
                $txtSalida.=$sumaTemp; 
            } 
            $asciiMensaje=$txtSalida; 
            $txtSalida=""; 
        } 
        
        if($this->modo=="en"){//encriptar 
            $textoFinal=$this->sustNumsXLetras($asciiMensaje); 
        }else{ 
            $textoFinal=$this->dameChr($asciiMensaje); 
        } 
        return $textoFinal; 
    } 

    private function sustNumsXLetras($str){ 
        $arrSust2Nums="öü|ijlmáéíóúnño{pqsuôûÂvwx#yzABCE=)(%&$·\\FGIJKLªMNÑ¿STUXYZÁÉÍbcdÓÚàèì}òùÀÈVWÌÒÙäëïÄ~ËÏÖÜâêîÊfhÎÔÛk?\"H-"; 
        $arrSust1Nums="agterOPRKD"; 
        $i=0; 
        $longStr=strlen($str); 
    
        while($i<$longStr){ 
            if($i+1<$longStr){//si hay 2 caracteres 
                $letra=""; 
                for($a=0;$a<2;$a++){ 
                    $letra.=$str[$i]; 
                    $i=$i+1; 
                } 
                $salida.=$arrSust2Nums[$letra]; 
            }else{//hay 1 caracter, osea que es el final 
                $salida.=$arrSust1Nums[$str[$i]]; 
                $i=$i+1; 
            } 
        } 
        return $salida; 
    } 
    
    private function sustLetrasXNums($str){ 
        $arrSust2Nums="öü|ijlmáéíóúnño{pqsuôûÂvwx#yzABCE=)(%&$·\\FGIJKLªMNÑ¿STUXYZÁÉÍbcdÓÚàèì}òùÀÈVWÌÒÙäëïÄ~ËÏÖÜâêîÊfhÎÔÛk?\"H-"; 
        $arrSust1Nums="agterOPRKD"; 
        $longStr=strlen($str); 
    
        for($i=0;$i<$longStr;$i++){ 
            $temp=strpos($arrSust2Nums,$str[$i]); 
            if($temp===false){ 
                $temp=strpos($arrSust1Nums,$str[$i]); 
            }else{ 
                if($temp<=9) { 
                    $temp="0$temp"; 
                } 
            } 
            
            $salida.="$temp"; 
        } 
        return $salida; 
    } 
    
    private function dameNumClaveInterna($a,$claveInterna){ 
        if($a==strlen($claveInterna)-1){ 
            $a=0; 
        }else{ 
            $a++; 
        } 
        return($claveInterna[$a]); 
    } 
    
    private function dameAsciisYLong($str){ 
        for($i=0;$i<strlen($str);$i++){ 
            $ascii=ord($str[$i]); 
            $lenAscii=strlen("$ascii"); 
            
            $salida.=$lenAscii.$ascii; 
        } 
        return $salida; 
    } 
    
    private function dameAsciis($str){ 
        for($i=0;$i<strlen($str);$i++){ 
            $ascii=ord($str[$i]); 
            $salida.=$ascii; 
        } 
        return $salida; 
    } 
    
    private function suma($a,$b){ 
        $resul=$a+$b; 
        return substr($resul,-1,1); 
    } 
    
    private function dameChr($str){ 
        $i=0; 
        while($i<strlen($str)){ 
            $long=$str[$i]; 
            for($a=0;$a<$long;$a++){ 
                $i=$i+1; 
                $resul.="{$str[$i]}"; 
            } 
            $letras.=chr((int) $resul); 
            $resul=""; 
            $i=$i+1; 
        } 
        return $letras; 
    } 

    private function resta($a,$b){ 
        $a=(int) $a; 
        $b=(int) $b; 
        for($i=0;$i<$b;$i++){ 
            if($a==0) {$a=10;} 
            $a=$a-1; 
        } 
        return substr($a,-1,1); 
    } 
    
    private function divisiblePor3($num){ 
        if($num%3==0) {return true;} else {return false;} 
    } 
} 
?> 