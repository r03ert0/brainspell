
#Documentation

*This documentation clarify the code in brainspell repository.*


###1. Replaced Variable vs. Comment

The ```<!-- Comments Here -->``` format in the HTML file represents regular comments, but the code with the % symbol ```<!--% SomeVariableWillBeReplaced %-->``` represents the variable that will be replaced by the corresponding variable from the PHP file. 

_**Example**_

In **site/templates/search.html** file:
```
<h2><b><!--%SearchResultsNumber%--> article<!--%SearchResultsMultiplicity%--> corresponding to the search "<!--%SearchString%-->"</b></h2>
```

*```<!--%SearchResultsNumber%-->```* is replaced by the number of articles (```count($hits)```) found with given query by:

```
$tmp=str_replace("<!--%SearchResultsNumber%-->", count($hits), $html);
```
 in the **site/php/brainspell.php** file.

 
