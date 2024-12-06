					<?if(!$isIndex):?>
						<?CAllcorp3Medc::checkRestartBuffer();?>
					<?endif;?>
					<?IncludeTemplateLangFile(__FILE__);?>
					<?global $arTheme, $isIndex, $is404;?>
					<?if(!$isIndex):?>
							<?if($is404):?>
								</div>
							<?else:?>
									<?CAllcorp3Medc::get_banners_position('CONTENT_BOTTOM');?>
									</div> <?// class=right_block?>
									<?if($APPLICATION->GetProperty("MENU") != "N" && !defined("ERROR_404")):?>
										<?CAllcorp3Medc::ShowPageType('left_block');?>
									<?endif;?>
								</div><?// class=col-md-12 col-sm-12 col-xs-12 content-md?>
							<?endif;?>
							<?if($APPLICATION->GetProperty("FULLWIDTH")!=='Y'):?>
								</div><?// class="maxwidth-theme?>
							<?endif;?>
						</div><?// class=row?>
					<?else:?>
						<?CAllcorp3Medc::ShowPageType('indexblocks');?>
					<?endif;?>
				</div><?// class=container?>
				<?CAllcorp3Medc::get_banners_position('FOOTER');?>
			</div><?// class=main?>
		</div><?// class=body?>
		<?CAllcorp3Medc::ShowPageType('footer');?>
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/bottom_footer.php'));?>
	</body>
</html>