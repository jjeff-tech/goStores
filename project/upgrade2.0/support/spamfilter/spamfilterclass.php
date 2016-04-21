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

/**************************************Mysql class******************************************************/
# ***** BEGIN LICENSE BLOCK *****
# Version: MPL 1.1/GPL 2.0/LGPL 2.1
#
# The contents of this file are subject to the Mozilla Public License Version
# 1.1 (the "License"); you may not use this file except in compliance with
# the License. You may obtain a copy of the License at
# http://www.mozilla.org/MPL/
#
# Software distributed under the License is distributed on an "AS IS" basis,
# WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
# for the specific language governing rights and limitations under the
# License.
#
# The Original Code is DotClear Weblog.
#
# The Initial Developer of the Original Code is
# Olivier Meunier.
# Portions created by the Initial Developer are Copyright (C) 2003
# the Initial Developer. All Rights Reserved.
#
# Contributor(s):
#
# Alternatively, the contents of this file may be used under the terms of
# either the GNU General Public License Version 2 or later (the "GPL"), or
# the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
# in which case the provisions of the GPL or the LGPL are applicable instead
# of those above. If you wish to allow use of your version of this file only
# under the terms of either the GPL or the LGPL, and not to allow others to
# use your version of this file under the terms of the MPL, indicate your
# decision by deleting the provisions above and replace them with the notice
# and other provisions required by the GPL or the LGPL. If you do not delete
# the provisions above, a recipient may use your version of this file under
# the terms of any one of the MPL, the GPL or the LGPL.
#
# ***** END LICENSE BLOCK *****

# Classe de connexion MySQL
$conn=getConnection();
class recordset
{
	var $arry_data;	# tableau contenant les donn�es
	var $int_index;	# index pour parcourir les enregistrements
					# les enregistrements commencent � l'index 0

	var $int_row_count;	# nombre d'enregistrements
	var $int_col_count;	# nombre de colonnes

	function recordSet($data)
	{
		$this->int_index = 0;

		if(is_array($data))
		{
			$this->arry_data = $data;

			$this->int_row_count = count($this->arry_data);

			if ($this->int_row_count == 0)
			{
				$this->int_col_count = 0;
			}
			else
			{
				$this->int_col_count = count($this->arry_data[0]);
			}
		}
	}

	function field($c)
	{
		if(!empty($this->arry_data))
		{
			if(is_integer($c))
			{
				$T = array_values($this->arry_data[$this->int_index]);
				return (isset($T[($c)])) ? $T[($c)] : false;
			}
			else
			{
				$c = strtolower($c);
				if(isset($this->arry_data[$this->int_index][$c]))
				{
					if (!is_array($this->arry_data[$this->int_index][$c])) {
						return trim($this->arry_data[$this->int_index][$c]);
					} else {
						return $this->arry_data[$this->int_index][$c];
					}
				}
				else
				{
					return false;
				}
			}
		}
	}

	function f($c)
	{
		return $this->field($c);
	}

	function setField($c,$v)
	{
		$c = strtolower($c);
		$this->arry_data[$this->int_index][$c] = $v;
	}

	function moveStart()
	{
		$this->int_index = 0;
		return true;
	}

	function moveEnd()
	{
		$this->int_index = ($this->int_row_count-1);
		return true;
	}

	function moveNext()
	{
		if (!empty($this->arry_data) && !$this->EOF()) {
	 		$this->int_index++;
			return true;
		} else {
			return false;
		}
	}

	function movePrev()
	{
		if (!empty($this->arry_data) && $this->int_index > 0) {
			$this->int_index--;
			return true;
		} else {
			return false;
		}
	}

	function move($index)
	{
		if (!empty($this->arry_data) && $this->int_index >= 0 && $index < $this->int_row_count) {
			$this->int_index = $index;
			return true;
		} else {
			return false;
		}
	}

	function BOF()
	{
		return ($this->int_index == -1 || $this->int_row_count == 0);
	}

	function EOF()
	{
		return ($this->int_index == $this->int_row_count);
	}

	function isEmpty()
	{
		return ($this->int_row_count == 0);
	}

	# Donner le tableau de donn�es
	function getData()
	{
		return $this->arry_data;
	}

	# Nombre de lignes
	function nbRow()
	{
		return $this->int_row_count;
	}
}
class Connection
{
	var $con_id;
	var $error;
	var $errno;

	function Connection($user, $pwd , $alias='', $dbname)
	{
	    global $conn;

		$this->error = '';

		$this->con_id =$conn;// @mysql_connect($alias, $user, $pwd);

		if (!$this->con_id) {
			$this->setError();
		} else {
			$this->database($dbname);
		}
	}

	function database($dbname)
	{
		$db = @mysql_select_db($dbname);
		if(!$db) {
			$this->setError();
		return false;
		} else {
			return true;
		}
	}

	function close()
	{
		if ($this->con_id) {
			mysql_close($this->con_id);
			return true;
		} else {
			return false;
		}
	}

	function select($query,$class='recordset')
	{
		if (!$this->con_id) {
			return false;
		}

		if ($class == '' || !class_exists($class)) {
			$class = 'recordset';
		}

		$cur = mysql_unbuffered_query($query, $this->con_id);

		if ($cur)
		{
			# Insertion dans le reccordset
			$i = 0;
			$arryRes = array();
			while($res = mysql_fetch_row($cur))
			{
				for($j=0; $j<count($res); $j++)
				{
					$arryRes[$i][strtolower(mysql_field_name($cur, $j))] = $res[$j];
				}
				$i++;
			}
			return new $class($arryRes);
		}
		else
		{
			$this->setError();
			return false;
		}
	}

	function execute($query)
	{
		if (!$this->con_id) {
			return false;
		}

		$cur = mysql_query($query, $this->con_id);

		if (!$cur) {
			$this->setError();
			return false;
		} else {
			return true;
		}
	}

	function getLastID()
	{
		if ($this->con_id) {
			return mysql_insert_id($this->con_id);
		} else {
			return false;
		}
	}

	function setError()
	{
		if ($this->con_id) {
			$this->error = mysql_error($this->con_id);
			$this->errno = mysql_errno($this->con_id);
		} else {
			$this->error = mysql_error();
			$this->errno = mysql_errno();
		}
	}

	function error()
	{
		if ($this->error != '') {
			return $this->errno.' - '.$this->error;
		} else {
			return false;
		}
	}

	function escapeStr($str)
	{
		//return mysql_escape_string($str);
                return mysql_real_escape_string($str);
	}
}

/**************************************Mysql class******************************************************/


/**********************************************************************************naivebayesian.php*/
/*
  ***** BEGIN LICENSE BLOCK *****
   This file is part of PHP Naive Bayesian Filter.

   The Initial Developer of the Original Code is
   Loic d'Anterroches [loic_at_xhtml.net].
   Portions created by the Initial Developer are Copyright (C) 2003
   the Initial Developer. All Rights Reserved.

   Contributor(s):
     See the source

   PHP Naive Bayesian Filter is free software; you can redistribute it
   and/or modify it under the terms of the GNU General Public License as
   published by the Free Software Foundation; either version 2 of
   the License, or (at your option) any later version.

   PHP Naive Bayesian Filter is distributed in the hope that it will
   be useful, but WITHOUT ANY WARRANTY; without even the implied
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
   See the GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Foobar; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

   Alternatively, the contents of this file may be used under the terms of
   the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
   in which case the provisions of the LGPL are applicable instead
   of those above.

  ***** END LICENSE BLOCK *****
*/


class NaiveBayesian
{
    /** min token length for it to be taken into consideration */
	var $min_token_length = 3;
    /** max token length for it to be taken into consideration */
    var $max_token_length = 15;
    /** list of token to ignore
        @see getIgnoreList()
     */
    var $ignore_list = array();
    /** storage object
        @see class NaiveBayesianStorage
    */
    var $nbs = null;

    function NaiveBayesian($nbs)
    {
    	$this->nbs = $nbs;
    	return true;
    }

    /** categorize a document.
    Get list of categories in which the document can be categorized
    with a score for each category.

        @return array keys = category ids, values = scores
        @param string document
    */
    function categorize($document)
    {

        $scores = array();
        $categories = $this->nbs->getCategories();
        $tokens = $this->_getTokens($document);

        if(count($tokens)<=0){
          $scores['spam']="0.9";
          $scores['notspam']="0.0";
		  return $this->_rescale($scores);
		}

        // calculate the score in each category
        $total_words = 0;
        $ncat = 0;
        while (list($category, $data) = each($categories)) {

            $total_words += $data['word_count'];
            $ncat++;
        }

        reset($categories);
        while (list($category, $data) = each($categories)) {
            $scores[$category] = $data['probability'];
            // small probability for a word not in the category
            // maybe putting 1.0 as a 'no effect' word can also be good
//echo $word['count']."::$small_proba::$category::$token :: $count <br>";
            $small_proba = 1.0/($data['word_count']*2);
            reset($tokens);
            while (list($token, $count) = each($tokens)) {

                if ($this->nbs->wordExists($token)) {
                    $word = $this->nbs->getWord($token, $category);
                    if ($word['count']) $proba = $word['count']/$data['word_count'];

                    else $proba = $small_proba;


                    $scores[$category] *= pow($proba, $count)*pow($total_words/$ncat, $count);
                   // pow($total_words/$ncat, $count) is here to avoid underflow.
                }
            }
        }
        return $this->_rescale($scores);
    }

    /** training against a document.
    Set a document as being in a specific category. The document becomes a reference
    and is saved in the table of references. After a set of training is done
    the updateProbabilities() function must be run.

        @see updateProbabilities()
        @see untrain()
        @return bool success
        @param string document id, must be unique
        @param string category_id the category id in which the document should be
        @param string content of the document
    */
    function train($doc_id, $category_id, $content)
    {
    	$tokens = $this->_getTokens($content);
        while (list($token, $count) = each($tokens)) {
            $this->nbs->updateWord($token, $count, $category_id);
        }
        $this->nbs->saveReference($doc_id, $category_id, $content);
        return true;
    }

    /** untraining of a document.
    To remove just one document from the references.

        @see updateProbabilities()
        @see untrain()
        @return bool success
        @param string document id, must be unique
    */
    function untrain($doc_id)
    {
        $ref = $this->nbs->getReference($doc_id);
    	$tokens = $this->_getTokens($ref['content']);
        while (list($token, $count) = each($tokens)) {
            $this->nbs->removeWord($token, $count, $ref['category_id']);
        }
        $this->nbs->removeReference($doc_id);
        return true;
    }

    /** rescale the results between 0 and 1.

        @author Ken Williams, ken@mathforum.org
        @see categorize()
        @return array normalized scores (keys => category, values => scores)
        @param array scores (keys => category, values => scores)
    */
    function _rescale($scores)
    {
        // Scale everything back to a reasonable area in
        // logspace (near zero), un-loggify, and normalize
        $total = 0.0;
        $max   = 0.0;
        reset($scores);
        while (list($cat, $score) = each($scores)) {

            if ($score >= $max) $max = $score;
        }
        reset($scores);
        while (list($cat, $score) = each($scores)) {
            $scores[$cat] = (float) exp($score - $max);
            $total += (float) pow($scores[$cat],2);
        }
        $total = (float) sqrt($total);
        reset($scores);
        while (list($cat, $score) = each($scores)) {
             $scores[$cat] = (float) $scores[$cat]/$total;
        }
        reset($scores);
        return $scores;
    }


    /** update the probabilities of the categories and word count.
    This function must be run after a set of training

        @see train()
        @see untrain()
        @return bool sucess
    */
    function updateProbabilities()
    {
        // this function is really only database manipulation
        // that is why all is done in the NaiveBayesianStorage
        return $this->nbs->updateProbabilities();
    }

    /** Get the list of token to ignore.
        @return array ignore list
    */
    function getIgnoreList()
    {
    	return array('the', 'that', 'you', 'for', 'and');
    }

    /** get the tokens from a string

        @author James Seng. [http://james.seng.cc/] (based on his perl version)

        @return array tokens
        @param  string the string to get the tokens from
    */
    function _getTokens($string)
    {
        $rawtokens = array();
        $tokens    = array();
        $string = $this->_cleanString($string);
        if (count(0 >= $this->ignore_list))
            $this->ignore_list = $this->getIgnoreList();
        $rawtokens = explode("[^-_A-Za-z0-9]+", $string);
        // remove some tokens
        while (list( , $token) = each($rawtokens)) {
            $token = trim($token);
            if (!(('' == $token)                             ||
                  (strlen($token) < $this->min_token_length) ||
                  (strlen($token) > $this->max_token_length) ||
                  (preg_match('/^[0-9]+$/', $token))         ||
                  (in_array($token, $this->ignore_list))
               ))
               $tokens[$token]++;
        }
        return $tokens;
    }

    /** clean a string from the diacritics

        @author Antoine Bajolet [phpdig_at_toiletoine.net]
        @author SPIP [http://uzine.net/spip/]

        @return string clean string
        @param  string string with accents
    */
    function _cleanString($string)
    {
        $diac =
            /* A */   chr(192).chr(193).chr(194).chr(195).chr(196).chr(197).
            /* a */   chr(224).chr(225).chr(226).chr(227).chr(228).chr(229).
            /* O */   chr(210).chr(211).chr(212).chr(213).chr(214).chr(216).
            /* o */   chr(242).chr(243).chr(244).chr(245).chr(246).chr(248).
            /* E */   chr(200).chr(201).chr(202).chr(203).
            /* e */   chr(232).chr(233).chr(234).chr(235).
            /* Cc */  chr(199).chr(231).
            /* I */   chr(204).chr(205).chr(206).chr(207).
            /* i */   chr(236).chr(237).chr(238).chr(239).
            /* U */   chr(217).chr(218).chr(219).chr(220).
            /* u */   chr(249).chr(250).chr(251).chr(252).
            /* yNn */ chr(255).chr(209).chr(241);
		return strtolower(strtr($string, $diac, 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn'));
    }

}
/**************************************************************************************end class*/


/***************************************Storage*******************************************************/
/*
  ***** BEGIN LICENSE BLOCK *****
   This file is part of PHP Naive Bayesian Filter.

   The Initial Developer of the Original Code is
   Loic d'Anterroches [loic_at_xhtml.net].
   Portions created by the Initial Developer are Copyright (C) 2003
   the Initial Developer. All Rights Reserved.

   Contributor(s):

   PHP Naive Bayesian Filter is free software; you can redistribute it
   and/or modify it under the terms of the GNU General Public License as
   published by the Free Software Foundation; either version 2 of
   the License, or (at your option) any later version.

   PHP Naive Bayesian Filter is distributed in the hope that it will
   be useful, but WITHOUT ANY WARRANTY; without even the implied
   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
   See the GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with Foobar; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

   Alternatively, the contents of this file may be used under the terms of
   the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
   in which case the provisions of the LGPL are applicable instead
   of those above.

  ***** END LICENSE BLOCK *****
*/

/** Access to the storage of the data for the filter.

To avoid dependency with respect to any database, this class handle all the
access to the data storage. You can provide your own class as long as
all the methods are available. The current one rely on a MySQL database.

methods:
    - array getCategories()
    - bool  wordExists(string $word)
    - array getWord(string $word, string $categoryid)

*/
class NaiveBayesianStorage
{
    var $con = null;

    function NaiveBayesianStorage($user, $pwd , $server, $dbname)
    {

    	$this->con = new Connection($user, $pwd , $server, $dbname);
    	return true;

    }

    /** get the list of categories with basic data.

        @return array key = category ids, values = array(keys = 'probability', 'word_count')
    */
    function getCategories()
    {
        $categories = array();
        $rs = $this->con->select('SELECT * FROM sptbl_spam_categories');
        while (!$rs->EOF()) {
            $categories[$rs->f('category_id')] = array('probability' => $rs->f('probability'),
                                                       'word_count'  => $rs->f('word_count')
                                                );
            $rs->moveNext();
        }
        return $categories;
    }

    /** see if the word is an already learnt word.
        @return bool
        @param string word
    */
    function wordExists($word)
    {
        $rs = $this->con->select("SELECT * FROM sptbl_spam_wordfreqs WHERE word='".$this->con->escapeStr($word)."'");
        return !$rs->isEmpty();
    }

    /** get details of a word in a category.
        @return array ('count' => count)
        @param  string word
        @param  string category id
    */
    function getWord($word, $category_id)
    {
        $details = array();
        $rs = $this->con->select("SELECT * FROM sptbl_spam_wordfreqs WHERE
                                    word='".$this->con->escapeStr($word)."' AND
                                    category_id='".$this->con->escapeStr($category_id)."'");
        if ($rs->isEmpty()) $details['count'] = 0;
        else $details['count'] = $rs->f('count');
        return $details;
    }

    /** update a word in a category.
    If the word is new in this category it is added, else only the count is updated.

        @return bool success
        @param string word
        @param int    count
        @paran string category id
    */
    function updateWord($word, $count, $category_id)
    {

    	$oldword = $this->getWord($word, $category_id);
    	//echo "oldwordcount==".$oldword['count'];
    	if (0 == $oldword['count']) {

            return $this->con->execute("INSERT INTO sptbl_spam_wordfreqs (word, category_id, count) VALUES
                                ('".$this->con->escapeStr($word)."',
                                 '".$this->con->escapeStr($category_id)."',
                                 '".$this->con->escapeStr((int)$count)."')");
        } else {

            return $this->con->execute("UPDATE sptbl_spam_wordfreqs SET count=count+".(int)$count."
                                        WHERE category_id = '".$this->con->escapeStr($category_id)."'
                                        AND word = '".$this->con->escapeStr($word)."'");
        }
    }

    /** remove a word from a category.

        @return bool success
        @param string word
        @param int  count
        @param string category id
    */
    function removeWord($word, $count, $category_id)
    {
    	$oldword = $this->getWord($word, $category_id);
    	if (0 != $oldword['count'] && 0 >= ($oldword['count']-$count)) {
            return $this->con->execute("DELETE FROM sptbl_spam_wordfreqs WHERE
                                word='".$this->con->escapeStr($word)."' AND
                                category_id='".$this->con->escapeStr($category_id)."'");
        } else {
            return $this->con->execute("UPDATE sptbl_spam_wordfreqs SET count-=".(int)$count."
                                        WHERE category_id = '".$this->con->escapeStr($category_id)."'
                                        AND word = '".$this->con->escapeStr($word)."'");
        }
    }

    /** update the probabilities of the categories and word count.
    This function must be run after a set of training

        @return bool sucess
    */
    function updateProbabilities()
    {
    	// first update the word count of each category
        $rs = $this->con->select("SELECT category_id, SUM(count) AS total FROM sptbl_spam_wordfreqs WHERE 1 GROUP BY category_id");
        $total_words = 0;
        while (!$rs->EOF()) {
            $total_words += $rs->f('total');
            $rs->moveNext();
        }
        $rs->moveStart();
        if ($total_words == 0) {
            $this->con->execute("UPDATE sptbl_spam_categories SET word_count=0, probability=0 WHERE 1");
            return true;
        }
        while (!$rs->EOF()) {
            $proba = $rs->f('total')/$total_words;
            $this->con->execute("UPDATE sptbl_spam_categories SET word_count=".(int)$rs->f('total').",
                                        probability=".$proba."
                                        WHERE category_id = '".$rs->f('category_id')."'");
            $rs->moveNext();
        }
        return true;
    }

    /** save a reference in the database.

        @return bool success
        @param  string reference if, must be unique
        @param  string category id
        @param  string content of the reference
    */
    function saveReference($doc_id, $category_id, $content)
    {
        return $this->con->execute("INSERT INTO sptbl_spam_references (id, category_id, content) VALUES
                                ('".$this->con->escapeStr($doc_id)."',
                                 '".$this->con->escapeStr($category_id)."',
                                 '".$this->con->escapeStr($content)."')");
    }

    /** get a reference from the database.

        @return array  reference( category_id => ...., content => ....)
        @param  string id
    */
    function getReference($doc_id)
    {
        $ref = array();
        $rs = $this->con->select("SELECT * FROM sptbl_spam_references WHERE id='".$this->con->escapeStr($doc_id)."'");
        if ($rs->isEmpty()) return $ref;
        $ref['category_id'] = $rs->f('category_id');
        $ref['content'] = $rs->f('content');
        $ref['id'] = $rs->f('id');
        return $ref;
    }

    /** remove a reference from the database

        @return bool sucess
        @param  string reference id
    */
    function removeReference($doc_id)
    {
        return $this->con->execute("DELETE FROM sptbl_spam_references WHERE id='".$this->con->escapeStr($doc_id)."'");
    }



}
/***************************************************************End storage***************************************/









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
        //echo '<p class="error"><strong>Error:</strong> You must provide a category name.</p>';
    } else {
        $con = new Connection($login, $pass, $server, $db);
        $con->execute("INSERT INTO sptbl_spam_categories (category_id) VALUES ('".$con->escapeStr($cat)."')");
        //echo "<p class='success'>The category has been just added.</p>";
    }
}

function remcat()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$cat = trim(strip_tags($_REQUEST['cat']));
	$cat = strtr($cat, ' ', '');
	if (strlen($cat) == 0) {
        //echo '<p class="error"><strong>Error:</strong> You must provide a category name.</p>';
    } else {
        $con = new Connection($login, $pass, $server, $db);
        $con->execute("DELETE FROM sptbl_spam_categories WHERE category_id='".$con->escapeStr($cat)."'");
        $con->execute("DELETE FROM sptbl_spam_references WHERE category_id='".$con->escapeStr($cat)."'");
        $con->execute("DELETE FROM sptbl_spam_wordfreqs WHERE category_id='".$con->escapeStr($cat)."'");
        $nb->updateProbabilities();
       // echo "<p class='success'>The category has been just removed.</p>";
    }
}

function train()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$docid = trim(strip_tags($_REQUEST['docid']));
	$docid = strtr($docid, ' ', '');
	if (strlen($docid) == 0) {
       // echo '<p class="error"><strong>Error:</strong> You must provide an indentifier for the document..</p>';
        return;
    }
	$cat = trim(strip_tags($_REQUEST['cat']));
	$cat = strtr($cat, ' ', '');
	if (strlen($cat) == 0) {
        //echo '<p class="error"><strong>Error:</strong> You must give an identifier for the category.</p>';
        return;
    }
	$doc = trim($_REQUEST['document']);
	if (strlen($doc) == 0) {
        //echo '<p class="error"><strong>Error:</strong> You must provide a document.</p>';
        return;
    }
    if ($nb->train($docid, $cat, $doc)) {
        $nb->updateProbabilities();
        //echo "<p class='success'>The filter has been trained.</p>";
    } else {
        //echo "<p class='error'>Error: Error training the filter.</p>";
    }
}

function untrain()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$docid = trim(strip_tags($_REQUEST['docid']));
	$docid = strtr($docid, ' ', '');
	if (strlen($docid) == 0) {
        //echo '<p class="error"><strong>Error:</strong> You must provide an identifier for the document.</p>';
        return;
    }
    if ($nb->untrain($docid, $cat, $doc)) {
        $nb->updateProbabilities();
       // echo "<p class='success'>The filter has been untrained.</p>";
    } else {
        //echo "<p class='error'>Error: Problem untraining the filter</p>";
    }
}

function cat()
{
	global $_REQUEST, $login, $pass, $server, $db, $nb;
	$doc = trim($_REQUEST['document']);

	if (strlen($doc) == 0) {
        //echo '<p class="error"><strong>Error:</strong> You must supply a document.</p>';
        return;
    }
    if(iSMorechrjunk($doc)=="1"){
	        $doc="";
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
	    if(iSMorechrjunk($doc)=="1"){
	        $doc="";
		  	$scores = $nb->categorize($doc);
	    	$finalscore=$scores['spam']/$scores['notspam'];
	    	return $finalscore;
		}

	if (strlen($doc) == 0) {
       return 0;
    }
    $scores = $nb->categorize($doc);
	$finalscore	= "1";
	if ($scores['notspam'] > 0)
	    $finalscore=$scores['spam']/$scores['notspam'];
    return $finalscore;
}

function iSMorechrjunk($doc){
    $junkcharcters=0;
    $notjunkcharacters=0;
    $i=0;
	while($i<=strlen($doc)){
	  $chr_ord=ord($doc[$i]);
	  if( ($chr_ord>=48 and $chr_ord<=57) or ($chr_ord>=64 and $chr_ord<=90) or ($chr_ord>=97 and $chr_ord<=122) ){
	    $notjunkcharacters++;
	  }else{
	    $junkcharcters++;
	  }
	 $i++;
    }

    if($junkcharcters>$notjunkcharacters){
	    $totalchar=$junkcharcters+$notjunkcharacters;
	    $junkcharpercentage=($junkcharcters/$totalchar)*100;
	    if($junkcharpercentage>80)
	      return 1;
	}else{
	     return 0;
	}
}
?>