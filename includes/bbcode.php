<?php

function code($texte)
{
//Smileys

		$texte = str_replace(':D', '<img src="../images/smileys/heureux.png" title="heureux" alt="heureux" />', $texte);
		$texte = str_replace(':lol: ', '<img src="./images/smileys/lol.png"title="lol" alt="lol" />', $texte);

		$texte = str_replace(':triste:', '<img src="../images/smileys/triste.png" title="triste" alt="triste" />',$texte);
		$texte = str_replace(':frime:', '<img src="../images/smileys/cool.png" title="cool" alt="cool" />', $texte);

		$texte = str_replace(':rire:', '<img src="../images/smileys/rire.png" title="rire" alt="rire" />', $texte);
		$texte = str_replace(':s', '<img src="../images/smileys/confus.png" title="confus" alt="confus" />', $texte);

		$texte = str_replace(':o', '<img src="../images/smileys/choc.png" title="choc" alt="choc" />', $texte);

		$texte = str_replace(':question:', '<img src="../images/smileys/question.png" title="?" alt="?" />', $texte);

		$texte = str_replace(':exclamation:', '<img src="../images/smileys/exclamation.png" title="!" alt="!" />',$texte);

		$texte = preg_replace('`\[g\](.+)\[/g\]`isU', '<strong>$1</strong>',
		$texte);
		//italique
		$texte = preg_replace('`\[i\](.+)\[/i\]`isU', '<em>$1</em>',
		$texte);
		//souligné
		$texte = preg_replace('`\[s\](.+)\[/s\]`isU', '<u>$1</u>', $texte);
		//lien



		$texte = preg_replace('#\[url\]((http|https)://[a-z0-9._/?=-]+)\[/url\]#i', '<a href="$1">$1</a>', $texte);

		//On retourne la variable texte
		return $texte;
}

?>