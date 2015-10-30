
Documentation
--------------------
This documentation records the clarification of the code in brainspell repositary. 


1. The ```<!-- comments here -->``` format in the HTML file represents regular comments, but the code with the ```<!--% something %-->``` represents the variable that will be replaced by the corresponding variable from the PHP file. 

**Example**

In site/templates/search.html file:
```
<h2><b><!--%SearchResultsNumber%--> article<!--%SearchResultsMultiplicity%--> corresponding to the search "<!--%SearchString%-->"</b></h2>
```

```<!--%SearchResultsNumber%-->``` is replaced by the number of articles (```count($hits)```) found with given query by:

```
$tmp=str_replace("<!--%SearchResultsNumber%-->",count($hits),$html);
```
 in the site/php/brainspell.php file.

 