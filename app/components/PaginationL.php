<?php
class PaginationL
{
	// Метод возвращает строку пагинации
	public static function linkPagination($page, $pagesCount) {

		if($pagesCount == 1) {
			$viewPagination = '';
			return $viewPagination;
		}
		
		$page1 = "<span class='pag' onclick='location.href=`/note/page/".($page - 2)."`;'>".($page - 2)."</span>";
		$page2 = "<span class='pag' onclick='location.href=`/note/page/".($page - 1)."`;'>".($page - 1)."</span>";
		$page3 = "<span class='pag' onclick='location.href=`/note/page/".($page + 1)."`;'>".($page + 1)."</span>";
		$page4 = "<span class='pag' onclick='location.href=`/note/page/".($page + 2)."`;'>".($page + 2)."</span>";
		
		if($page - 2 < 2) {
			$page1 = '';
		}
		if($page - 1 < 2) {
			$page2 = '';
		}
		if($page + 1 >= $pagesCount) {
			$page3 = '';
		}
		if($page + 2 >= $pagesCount) {
			$page4 = '';
		}

		$viewPagination = "
			<span class='pag' onclick='location.href=`/note/page/1`;'>1 <</span>"
			.$page1.$page2.$page3.$page4.
			"<span class='pag' onclick='location.href=`/note/page/$pagesCount`;'>> $pagesCount</span>";
		
		return $viewPagination;
	}
}
?>