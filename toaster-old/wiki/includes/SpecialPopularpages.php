<?php
/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 *
 */
require_once( "QueryPage.php" );

/**
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class PopularPagesPage extends QueryPage {

	function getName() {
		return "Popularpages";
	}

	function isExpensive() {
		# cur_counter is not indexed
		return true;
	}
	function isSyndicated() { return false; }

	function getSQL() {
		$dbr =& wfGetDB( DB_SLAVE );
		$cur = $dbr->tableName( 'cur' );

		return
			"SELECT 'Popularpages' as type,
			        cur_namespace as namespace,
			        cur_title as title,
			        cur_counter as value
			FROM $cur
			WHERE cur_namespace=0 AND cur_is_redirect=0";
	}

	function formatResult( $skin, $result ) {
		global $wgLang, $wgContLang;
		$link = $skin->makeKnownLink( $result->title, $wgContLang->convert( $result->title ) );
		$nv = wfMsg( "nviews", $wgLang->formatNum( $result->value ) );
		return "{$link} ({$nv})";
	}
}

/**
 * Constructor
 */
function wfSpecialPopularpages() {
    list( $limit, $offset ) = wfCheckLimits();
    
    $ppp = new PopularPagesPage();
    
    return $ppp->doQuery( $offset, $limit );
}

?>
