<?php
/**
 * @name Core
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @package phpf
 * @version 1.0
 *
 */
class Core {
   
    function strip($val) {
        $val= ltrim($val, "'.");
        $val=rtrim($val, ".'");
        return $val;
    }
    function startOutTag($args) {
        $data =$args["value"];
        // $data= trim(str_replace(".","->",$data));
        return "<?php echo('$data'); ?>";
    }
    function endOutTag() {
        return "";
    }


    function startForTag($args) {
        $var = $args["var"];
        $to = $args["to"];
        $step = $args["step"];
        $begin = $args["begin"];
        if($begin>$to)
            return "<?php for($var=$begin;$var>=$to;$var-=$step): ?>";
        return "<?php for($var=$begin;$var<=$to;$var+=$step): ?>";
    }

    function endForTag() {
        return "<?php endfor; ?>";
    }

    function startIfTag($args) {
        $testr= $this->strip($args["test"]);

        return "<?php if($testr): ?>";
    }

    function endifTag() {
        return "<?php endif; ?>";
    }


    function startElseIfTag($args) {
        $testr= $this->strip($args["test"]);
        return "<?php elseif($testr): ?>";
    }

    function endElseIfTag() {
        return "";
    }


    function startElseTag($args) {
        return "<?php else: ?>";
    }

    function endElseTag() {
        return "";
    }

    function startPhpTag($args) {
        return "<?php ".$args["code"]."?>";
    }

    function endPhpTag() {
    }

    function startForeachTag($args) {
        $var =  $this->strip($args["var"]);
        $item = $this->strip($args["item"]);
        return "<?php if($var) foreach($var as $item): ?>";
    }

    function endForeachTag() {
        return "<?php endforeach; ?>";
    }
}
?>