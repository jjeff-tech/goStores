<?php
/*
  ***** BEGIN LICENSE BLOCK *****
   This file is part of PHP Naive Bayesian Filter.

   The Initial Developer of the Original Code is
   Loic d'Anterroches [loic_at_xhtml.net].
   Portions created by the Initial Developer are Copyright (C) 2003
   the Initial Developer.

   Contributor(s): J Wynia - English Translation

   PHP Naive Bayesian Filter is free software; you can redistribute it
   and/or modify it under the terms of the GNU General Public License as
   published by the Free Software Foundation; either version 2 of
   the License, or (at your option) any later version.

   PHP Naive Bayesian Filter is distributed in the hope that it will
   be useful, but WITHOUT ANY WARRANTY; without even the implied
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
   See the GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with PHP Naive Bayesian Filter; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

  ***** END LICENSE BLOCK *****
*/
/*
This is a small example to give you an idea of how to use the class
You can create the necessary tables with the database definition file mysql.sql.
This file shouldn't be used on a live web server. It's just a proof of concept and example
to present the use of the filter. It doesn't have proper error handling or security...
*/

/* BEGIN CONFIGURATION */
$login  = $glb_dbuser;
$pass   = $glb_dbpass;
$db     = $glb_dbname;
$server = $glb_dbhost;
/* END CONFIGURATION */

include_once 'includes/class.naivebayesian.php';
include_once 'includes/class.naivebayesianstorage.php';
include_once 'includes/class.mysql.php';


$nbs = new NaiveBayesianStorage($login, $pass, $server, $db);
$nb  = new NaiveBayesian($nbs);


?>
<?php

switch ($_REQUEST['action']) {
case 'addcat':
    addcat();
    break;
case 'remcat':
    remcat();
    break;
case 'train':
    train();
    break;
case 'untrain':
    untrain();
    break;
case 'cat':
    cat();
    break;
}

function addcat()
{
	global $_REQUEST, $login, $pass, $server, $db;
	$cat = trim(strip_tags($_REQUEST['cat']));
	$cat = strtr($cat, ' ', '');
	if (strlen($cat) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must provide a category name.</p>';
    } else {
        $con = new Connection($login, $pass, $server, $db);
        $con->execute("INSERT INTO sptbl_spam_categories (category_id) VALUES ('".$con->escapeStr($cat)."')");
        echo "<p class='success'>The category has been just added.</p>";
    }
}

function remcat()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$cat = trim(strip_tags($_REQUEST['cat']));
	$cat = strtr($cat, ' ', '');
	if (strlen($cat) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must provide a category name.</p>';
    } else {
        $con = new Connection($login, $pass, $server, $db);
        $con->execute("DELETE FROM sptbl_spam_categories WHERE category_id='".$con->escapeStr($cat)."'");
        $con->execute("DELETE FROM sptbl_spam_references WHERE category_id='".$con->escapeStr($cat)."'");
        $con->execute("DELETE FROM sptbl_spam_wordfreqs WHERE category_id='".$con->escapeStr($cat)."'");
        $nb->updateProbabilities();
        echo "<p class='success'>The category has been just removed.</p>";
    }
}

function train()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$docid = trim(strip_tags($_REQUEST['docid']));
	$docid = strtr($docid, ' ', '');
	if (strlen($docid) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must provide an indentifier for the document..</p>';
        return;
    }
	$cat = trim(strip_tags($_REQUEST['cat']));
	$cat = strtr($cat, ' ', '');
	if (strlen($cat) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must give an identifier for the category.</p>';
        return;
    }
	$doc = trim($_REQUEST['document']);
	if (strlen($doc) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must provide a document.</p>';
        return;
    }
    if ($nb->train($docid, $cat, $doc)) {
        $nb->updateProbabilities();
        echo "<p class='success'>The filter has been trained.</p>";
    } else {
        echo "<p class='error'>Error: Error training the filter.</p>";
    }
}

function untrain()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$docid = trim(strip_tags($_REQUEST['docid']));
	$docid = strtr($docid, ' ', '');
	if (strlen($docid) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must provide an identifier for the document.</p>';
        return;
    }
    if ($nb->untrain($docid, $cat, $doc)) {
        $nb->updateProbabilities();
        echo "<p class='success'>The filter has been untrained.</p>";
    } else {
        echo "<p class='error'>Error: Problem untraining the filter</p>";
    }
}

function cat()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$doc = trim($_REQUEST['document']);
	if (strlen($doc) == 0) {
        echo '<p class="error"><strong>Error:</strong> You must supply a document.</p>';
        return;
    }
    $scores = $nb->categorize($doc);
    echo "<table><caption>Scores</caption>\n";
    echo "<tr><th>Category</th><th>Score</th></tr>\n";
    while(list($cat,$score) = each($scores)) {
        echo "<tr><td>$cat</td><td>$score</td></tr>\n";
    }
    echo "</table>";
}
function parsercat()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$doc = trim($_REQUEST['document']);
	if (strlen($doc) == 0) {
       return 0;
    }
    $scores = $nb->categorize($doc);
    $finalscore=$scores['spam']/$scores['notspam'];
    return $finalscore;
}


?>