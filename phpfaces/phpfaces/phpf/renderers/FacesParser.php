<?php
/**
 * @name Faces Parser
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf.renderers
 * @version 1.0
 *  parsing edilen tagler $parrsedArray içinde tutulur
 *  $beggin içinde açılan taglar tutulur bir tag kapandığında buradaki tagile
 *  karşılaştırılır kapanan tag $beggin içinden atılır.
 *  $compilerTags renderra has tagler tutulur
 */
class FacesParser
{
    var $parsedArray=array();
    var $beggin=array(array("yok"=>"0"));
    var $ptr=0;//for loop
    var $charData;
    var $data;
    var $charCount;
    var $tagName;
    var $between;
    var $start=-1;
    var $end=-1;
    var $currentTag;
    var $compilerTags=array();
    var $content;
    var $isExp=true;//true ise #{$test} php ye cevirir
    var $isHTML=false;//true ise html yide parse eder
    var $insertid=0;
    private $lineNumber=1;//satırlar
    private $compiler;
    private $filename;
    private $cOnek="@";//compiler on eki
    private $onEksize;
    const END_TAG= "END_TAG";
    const START_END_TAG= "START_END_TAG";
    const START_TAG= "START_TAG";
    const CHARS_DATA ="CAHRS";
    const TEXT_NODE = "TEXTNODE";
    const PHP_NODE = "PHPNODE";

    function FacesParser(IFacesRenderer $compiler=null)
    {
        $this->parsed=array();
        $this->compiler = $compiler;
        $this->onEksize= strlen($this->cOnek);
    }

    public function setConek($cOnek) {
        $this->cOnek = $cOnek;
        $this->onEksize= strlen($this->cOnek);
    }
    function getParsedArray(){
        return $this->parsedArray;
    }
    function getInsertId(){
        return $this->insertid;
    }


    function countLine() {
        if($this->data[$this->ptr]=="\n" )
        $this->lineNumber++;
    }
       /**
        * en son açılan tagı getirir
        * @return Array Tag
        *
        */
    function getEndOpenTag()
    {
        $begin=  end($this->beggin);
        return $begin["id"];
        return $this->parsedArray[$id];
    }
/**
 *
 * @return Integer
 * en son açılan tagin indisini getirir enson açılan tag ile son kapnan tag aynı degilse hata verir
 */
    function getBeginID() {
        $val= end($this->beggin);
        if($val["name"]==$this->tagName){
            $id=$val["id"];
            array_pop($this->beggin);
            return $id;
        }
        throw new ErrorException("Faces Parsing Error <b>Tag closing error exception</b> <li> Tag Name : <b> $this->tagName </b></li>" ,1,0,$this->filename,$this->lineNumber);
        // $this->error("Tag kapama hatasi tag adi : <b> $this->tagName </b>in <b>$this->filename</b> on line <b>$this->lineNumber</b></br> Cozum <b>$this->tagName</b> kapamadan once  <b>".$val['name']."</b> kapatin <b> </b> on line <b>".$val['line']."</b>" );
    }
/**
 *
 * <Faces> </faces>
 * her arası parsing işlemi sona erdiğinde geride açık tag varmı
 * diye kontrol eder varsa hata verir
 */
    function isTagClosings() {
        $val= end($this->beggin);
        if(isset($val["name"]))
        {
            throw new ErrorException("Faces Parsing Error <b>Tag closing error exception</b> <li> Tag Name : <b> $this->tagName </b></li>" ,1,0,$this->filename,$this->lineNumber);
            // $this->error("Parse Error tag kapatilmamis  tag ismi : <b>". $val["name"]."</b> in <b>$this->filename</b> on line <b>".$val["line"]."</b>" );
        }

    }
/**
 * <?php ye raslanldığında çalışır
 *
 */
    function processPHPTag() {
        $php="";

        $this->ptr+=4;
        for($this->ptr++;$this->ptr<=$this->charCount;$this->ptr++){
            $this->countLine();
            if($this->data[$this->ptr]=='?'&&$this->data[$this->ptr+1]==">")
            break;
            $php.=  $this->data[$this->ptr];
        }$this->ptr++;
        $this->currentTag=array("name"=>"php","code"=>$php,"line"=>$this->lineNumber,"type"=>FacesParser::PHP_NODE);
        $this->compiler->startingPHPTag($this->currentTag);
        array_push($this->parsedArray, $this->currentTag);
    }
/**
 *
 * bir tag içinde " karekterine ulaşıldığında çalışır
 */
    function tagInside() {
        $this->charData.=$this->data[$this->ptr];
        for($this->ptr++;$this->ptr<=$this->charCount;$this->ptr++){
            $this->countLine();
            if($this->data[$this->ptr]!='"')
            $this->charData.=$this->data[$this->ptr];
            else{
                $this->charData.=$this->data[$this->ptr];
                break;
            }
        }
    }

    //tag bitiginde bu tagın altındaki metinleri yakalar
    function tagOutSide() {
        $string="";
        for($this->ptr++;$this->ptr<$this->charCount;$this->ptr++){
            if($this->data[$this->ptr]=='<'){
                $this->ptr--;
                break;
            }
            else{
                $string.=($this->data[$this->ptr]);
                $this->countLine();
            }
        }
        return $string;
    }
    //tagi kapatır
    function closeTag() {
        $name = trim(substr($this->between,1));
        $urn = explode ( ":", $name );
        $nameSpace="";
        if(count($urn)>1)
        {
            $this->tagName= $urn[1];
            $nameSpace=$urn[0];
        }
        else
        $this->tagName= $name;
        $lastid =$this->getBeginID();
        $this->currentTag=array("type"=>FacesParser::END_TAG,"name"=>$this->tagName,"line"=>$this->lineNumber,"beginid"=>$lastid,"namespace"=>$nameSpace);
        $this->insertid=   array_push($this->parsedArray,$this->currentTag);

        $this->parsedArray[$lastid]["closeid"]=$this->insertid-1;
        $this->compiler->closingTag($this->currentTag);
        $this->charData="";
        $this->start=-1;
        $this->end=-1;
    }
    function closeCTag()
    {
        $name = trim(substr($this->between,1));
        $this->tagName= $name;
        $lastid =$this->getBeginID();
        $this->currentTag=array("type"=>FacesParser::END_TAG,"name"=>$this->tagName,"line"=>$this->lineNumber,"beginid"=>$lastid);
        $this->insertid=   array_push($this->parsedArray,$this->currentTag);
        $this->parsedArray[$lastid]["closeid"]=$this->insertid-1;
        $this->compiler->closingCompilerTag($this->currentTag);
        $this->charData="";
        $this->start=-1;
        $this->end=-1;
    }

    function addTextNode($string)
    {
        $string= $this->toFacesExp($string);
        $textNode=array("text"=>$string,"line"=>$this->lineNumber,"type"=>FacesParser::TEXT_NODE);
        $this->compiler->textNode($string);
        array_push($this->parsedArray,$textNode);
    }
    //** tag parsing edilmeyen türdeyse bu tag escape edilir
    function escapeTag() {
        $tags= "<".$this->between.">";
        $string = $this->tagOutSide();
        $this->addTextNode($tags.$string);
    }
    //dosyayı parse eder
    function parseFile($filename){
        $this->filename= $filename;
       return $this->parse(file_get_contents($filename));
    }
    //veriyi parse eder
    function parse($data) {
        $this->content =" ".$data;
        $fend=0;
        $bsize= strlen("<faces>");
        $esize= strlen("</faces>");
       $head = stripos($this->content, "<head>",0);
     
       if($head)
       {
       $this->content =substr($this->content,0,$head+6)."<?php echo \$header->startHead();?>". substr($this->content,$head+6);
       }
      /**  echo substr($this->content,0,$head+6)."<?php \$this->header = new Head(\$this)?>". substr($this->content,$head+6);*/
        while(($fbegin = stripos($this->content, "<faces>",0))){
            $fbegin +=$bsize;
            $fend =  stripos($this->content, "</faces>",$fbegin);
            if(!$fend)

            throw new ErrorException("Parsing error require <b>". htmlspecialchars("</faces>")."</b> <li> faces close tag <b> $this->tagName </b></li>" ,1,0,$this->filename,$this->lineNumber);
            $this->data=  substr($this->content, $fbegin, $fend-$fbegin);
            $text= substr($this->content, 0,$fbegin-$bsize);
            $this->content = substr($this->content,$fend+$esize);

            $this->lineNumber+= substr_count($text,"\n");
            $this->charCount = $fend;
            $this->start=-1;
            $this->end=-1;
            $this->charData="";
            $this->compiler->startFaces($text);
            $this->textControl2();
            $this->isTagClosings();
            $this->compiler->closingFaces("");
        }
        $text =substr($this->content, 0);
        $this->compiler->closingFaces($text);
        $this->content=NULL;
  
    }
/**
 * {$data} cevirir
 */
    function toFacesExp($text,$type="echo") {
        if(!$this->isExp)
        return $text;
        $end=0;
        while(($begin = strpos($text, '#{',$end))!==FALSE)
        {
            $end = strpos($text, "}",$begin);
            if(!$end)
            break;

            $aralik = substr($text, $begin,$end-$begin+1);
            if($type=="echo")
            $cevrilen= preg_replace ( "[}]", "; ?>", str_replace ( array("{",".","#"), array("<?php echo ","->",""), $aralik) );
            elseif($type=="tag")
            $cevrilen= "'.(".preg_replace ( "[}]", "", str_replace ( array("{",".","#","'"), array("","->","",""),$aralik) ).").'";

            $text= str_replace($aralik,$cevrilen,$text);
        }
        return $text;
    }
/**
 * Parsing işleminin yapıldıgı esas bölüm
 *
 */
    function textControl2() {
        for($this->ptr =0;$this->ptr<=$this->charCount;$this->ptr++){
            $currentChar =$this->data[$this->ptr];
            $this->countLine();
            if($currentChar=='<' )
            {
                if($this->data[$this->ptr+1]=='?'){
                    $this->processPHPTag();
                    $this->start=-1;
                    continue ;
                }
                else
                {
                    $this->start=$this->ptr;
                    continue;
                }
            }
            elseif($this->data[$this->ptr]=='"'&& $this->start!=-1)
            {
                $this->tagInside();
                continue;
            }
            elseif($this->data[$this->ptr]=='>')
            {
                $this->end=$this->ptr;
                if($this->start!=-1 && $this->end!=-1)
                {
                    $legnht = ($this->end-$this->start)-1;
                    $this->between = (substr($this->data, $this->start+1,$legnht));
                    $sizeofaralik = strlen($this->between);
                      
                    $textContent = (substr($this->charData,0,(strlen(($this->charData)))-$sizeofaralik ));
                    if(strlen(trim($textContent))){
                        $this->addTextNode($textContent);
                    }
                    $this->charData="";

                    $isCompilerTag= $this->isCompilerTag($this->between);
                    if(!$this->compiler->isCompilerNS(($this->getStringNameSpace($this->between)))&&($this->isHTML==false))
                    {
                        if(!$isCompilerTag)
                        {
                            
                            $this->escapeTag();
                            continue;
                        }
                    }
                    //**Close Tag
                    if($this->between[0]=='/'){

                        if(!$isCompilerTag)
                        $this->closeTag();
                        else
                        $this->closeCTag();
                        continue;
                    }

                    if($this->between[$sizeofaralik-1]=='/'){
                        $this->currentTag= $this->parseTagText(substr($this->between,0,$sizeofaralik-1));
                        $this->currentTag["type"]=FacesParser::START_END_TAG;
                    }
                    else
                    {
                        $this->currentTag= $this->parseTagText($this->between);
                        $this->currentTag["type"]=FacesParser::START_TAG;
                        $this->currentTag["closeid"]=-1;
                    }
                    //compiler tags
                    if (strcasecmp($this->cOnek, substr($this->tagName,0,$this->onEksize))==0)
                    {
                        $this->charData="";
                        $this->compiler->compilerTag(&$this->currentTag,$this->between);
                        $this->insertid = array_push($this->parsedArray,$this->currentTag);
                        if($this->currentTag["type"]==FacesParser::START_TAG)
                        array_push($this->beggin,array("name"=>$this->tagName,"id"=>$this->insertid-1,"line"=>$this->lineNumber));
                        continue;
                    }

                    $this->compiler->startingTag($this->currentTag);
                    $this->insertid= array_push($this->parsedArray,$this->currentTag);
                    if($this->currentTag["type"]==FacesParser::START_TAG)
                    array_push($this->beggin,array("name"=>$this->tagName,"id"=>$this->insertid-1,"line"=>$this->lineNumber));
                    //inner Text dedection
                    $string = $this->tagOutSide();
                    if(strlen(trim($string)))
                    $this->addTextNode($string);
                    $this->start=-1;
                    $this->end=-1;
                }
            }else
            $this->charData.=$this->data[$this->ptr];
        }
    }

    //**get name of prefix
    function getStringNameSpace($string)
    {
        $name = strtok($string, " ");
        $nameSpace="";
        $urn = explode ( ":", $name );
        if(count($urn)>1){
            if($urn[0][0]=='/')
            return substr($urn[0], 1);
            return $urn[0];
        }
        return false;
    }
    /** bir tagin renderer tagi olup olmadığına bakar*/
    function isCompilerTag($string)
    {$i=0;
        if($string[0]=="/")
        $i=1;
        return (strcasecmp($this->cOnek, substr($string,$i,$this->onEksize))==0);
    }
    //** tag işler isim = deger sekline donustutur
    function parseTagText($string)
    {
        $string= $this->toFacesExp($string,"tag");
        $attributes=array();
        $name = strtok($string, " ");
        $nameSpace="";
        $urn = explode ( ":", $name );
        if(count($urn)>1){
            $this->tagName= $urn[1];
            $nameSpace=$urn[0];
        }
        else{
            $this->tagName= $name;
        }
        $tag= array("name"=>$this->tagName,"attributes"=>array(),"namespace"=>$nameSpace);
        $attName = strtok('"');
        do
        {
            $attName=  trim(str_replace("=", "", $attName));
            $value = trim(strtok('"'));//trim ?
            if(!$attName)
            break;
            if($value[0]=='"'&& $value[strlen($value)-1]=='"')
            $value=  substr($value, 0,(strlen($value)-2));
            else
            $value= substr($value, 0,(strlen($value)));

            if($attName=="bind"&&$this->isExp)
            $value= str_replace(array("'","."), array("","->"),$value);

            $tag["attributes"][$attName]= $value;

        }
        while (($attName = strtok('"'))!=false) ;
        $tag["line"]=$this->lineNumber;
        return $tag;
    }
}
?>
