<?php
$item = get_current_item();

require('tfpdf.php');

class PDF extends tFPDF
{
	function Header()
	{
    	$this->SetFont('Arial','B',20);
    	$this->Cell(190,10,'Basel Mission Archives','B',1,'C');
    	$this->Ln(10);
	}
	
	function ChapterTitle($label)
    {
        $this->SetFont('Arial','B',13);
        $this->Cell(40,6,"$label");
    }
    
    function ChapterBody($txt)
    {
        $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $this->SetFont('DejaVu','',12);
        $this->MultiCell(0,5,strip_tags($txt));
    }
	
	function PrintChapter($title, $txt)
    {
        $txt = html_entity_decode($txt, ENT_QUOTES);
		$title = html_entity_decode($title, ENT_QUOTES);
        $this->ChapterTitle($title);
        $this->ChapterBody($txt);
        $this->Ln(4);
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetTitle($title);
$pdf->SetAuthor('BM Archives');

$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
$pdf->SetFont('DejaVu','',13);
$pdf->MultiCell(0,5,'"'.html_entity_decode(strip_tags(item('Dublin Core', 'Title')), ENT_QUOTES).'"');
$pdf->Ln(2);

preg_match('/src=\"([^\"]+)\"/', item_fullsize(), $m);
if ($m){
	$pdf->Image($m[1],null,null,0,133,'JPEG');
	$pdf->Ln(10);
}

$title = '"'.item('Dublin Core', 'Title').'"';
if ($title != ""){
	$pdf->PrintChapter('Title:', $title);
}

$alt_title = item('Dublin Core', 'Title', 'all');
if ($alt_title[1] != ""){
	$tmp = '';
	foreach (array_slice($alt_title, 1) as $t){
		$tmp = $tmp.$t."\n";
	}
	$pdf->PrintChapter('Alternate title:', $tmp);
}

$identifier = item('Dublin Core', 'Identifier');
if ($identifier){
	$pdf->PrintChapter('Ref. number:', $identifier);
}

$creator = item('Dublin Core', 'Creator');
if ($creator){
	$pdf->PrintChapter('Creator:', $creator);
}

$dates = item('Dublin Core', 'Date', 'all');
if ($dates){
	$tmp = '';
	foreach (array_slice($dates, 1) as $d){
		$tmp = $tmp.$d."\n";
	}
	$pdf->PrintChapter('Date:', $tmp);
}

$description = item('Dublin Core', 'Description');
if ($description){
	$pdf->PrintChapter('Description:', $description);
}

$subject = item('Dublin Core', 'Subject');
if ($subject){
	$pdf->PrintChapter('Subject:', $subject);
}

$type = $item->getItemType()->name;
if ($type){
	$pdf->PrintChapter('Type:', $type);
}

$format = item('Dublin Core', 'Format');
if ($format){
	$pdf->PrintChapter('Format:', $format);
}

$relation = item('Dublin Core', 'Relation');
if ($relation){
	$pdf->PrintChapter('Relation:', $relation);
}

//$pdf->Cell(190,2,'','B',1,'C');
$pdf->Cell(190,2,'',0,1,'C');
$pdf->Ln(5);

$pdf->PrintChapter('Ordering:', 'Please contact us by email info@bmarchives.org');

$pdf->PrintChapter('Contact details:', 'Basel Mission Archives/ mission 21, Missionstrasse 21, 4003 Basel, tel. (+41 61 260 2232), fax: (+41 61 260 2268), info@bmarchives.org');

$pdf->PrintChapter('Rights:', 'All the images (photographic and non- photographic) made available in this collection are the property of the Basel Mission / mission 21. The Basel Mission claims copyright on the images in their possession and requires those - both individuals and organisations - publishing any of the images, to pay a users fee.');

$pdf->Output(preg_replace('/[^A-Za-z0-9_\-\.]/','_', $identifier).'.pdf','D');

?>
