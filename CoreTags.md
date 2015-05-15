Use core tags
<@import prefix="c" taglib="phpf.core" type="static"/>

<b>out tag<b>
parameters value<br>
<blockquote>sample out<br>
<pre><code>&lt;c:out value="hi all"/&gt;<br>
See this code makes<br>
echo "hi all"<br>
</code></pre></blockquote>


<b>if tag<b>
parameters test<br>
<blockquote>sample if<br>
<pre><code>&lt;c:if test="#{$name=='bora'}"&gt;<br>
&lt;c:out value="#{$name}"/&gt;<br>
&lt;/c:if&gt;<br>
</code></pre></blockquote>

<b>else tag<b>
<blockquote>sample else<br>
<pre><code>&lt;c:if test="#{$name=='bora'}"&gt;<br>
&lt;c:out value="#{$name}"/&gt;<br>
&lt;c:else&gt;<br>
&lt;c:out value="other name #{$name}"/&gt;<br>
&lt;/c:else&gt;<br>
&lt;/c:if&gt;<br>
</code></pre></blockquote>

<b>elseif tag<b>
parameters test<br>
<blockquote>sample elseif<br>
<pre><code>&lt;c:if test="#{$name=='bora'}"&gt;<br>
&lt;c:out value="#{$name}"/&gt;<br>
&lt;c:elseif test="#{$name=='obama'}"&gt;<br>
&lt;c:out value="name obama"/&gt;<br>
&lt;/c:elseif&gt;<br>
&lt;/c:if&gt;<br>
</code></pre></blockquote>

<b>for tag<b>
parameters var ,begin, to,step<br>
<blockquote>sample for<br>
<pre><code>&lt;c:for var="$i" begin="1" to="10" step="1"&gt;<br>
&lt;c:out value="#{$i}"/&gt;<br>
&lt;/c:for&gt;<br>
</code></pre>
Out 1,2,3,4,5,6,7,9,10</blockquote>


<b>foreach tag<b>
parameters var, item<br>
<blockquote>sample forech<br>
<pre><code>&lt;c:foreach var="$this.list" item="$item"&gt;<br>
  &lt;br/&gt;<br>
   Name :&lt;c:out value="#{$item.name}"/&gt;<br>
   Id :&lt;c:out value="#{$item.id}"/&gt;<br>
  &lt;/c:foreach&gt;<br>
</code></pre>