<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/header.php"; ?>
<?
$list = $route->getFaqList();
?>
    <script>
        $(document).ready(function(){
        });
    </script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>FAQ</h2>
                            <p>자주 묻는 질문</p>
						</header>

						<!-- Content -->
							<section id="content">
                                <div class="row gtr-uniform gtr-50">
                                    <div class="col-12 col-12-xsmall">
                                        <? foreach ($list as $item){ ?>
                                            <div class="collapsible">
                                <span style="font-size: 14px;">
                                    <i class="fa fa-question-circle"></i>&nbsp;&nbsp;<?=$item["title"]?>
                                </span>
                                            </div>
                                            <div class="collapsible_content">
                                                <p class="faq-answer"><?=$item["content"]?></p>
                                            </div>
                                        <?}?>
                                    </div>
                                </div> <!-- end row -->
							</section>

					</div>
				</div>

			<!-- Footer -->
<? include_once $_SERVER["DOCUMENT_ROOT"]."/mygift/inc/footer.php"; ?>