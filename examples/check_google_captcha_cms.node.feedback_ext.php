<?
$CMS_NODE_TYPES[99] = array(
	"_class"=>"cms_node_feedback_ext",
	"_name"=>"раздел обратной связи (расширенный)",
	"_type"=>(99 << CMS_TYPE_BITS),
	"_permissions" => 7,
	"_development"=>false,
	"_pnames"  => array(array("просмотр узла","show"),array("добавление записей","add"),array("просмотр списка записей","list")),
	"_actions" => array("show"=>0,"_entry.add"=>1,"entries.list"=>2,"entry.show"=>2,"_recaptcha"=>0),
	"_config"  => array("fstatuses"=>"новое;в рассмотрении;ответ отправлен","fprefreg"=>'',"nform2admin"=>1,"flettertitle"=>"Feedback data", "frequest_link"=>"lnk", "fwidth"=>500, "fresultwidth"=>200, "fresult"=>0, "fmethod"=>0, "ftarget"=>0, "nblocks"=>"2,1","nmap"=>"","nroot"=>"","nsort"=>0,"nshow"=>0,"natpage"=>0,"npages"=>0,"ntype"=>0,"nfields"=>0,"ndate"=>0,"nmail"=>0,"fmail"=>"","fsubmit"=>"","msubmit"=>"","nantispam"=>0, "nreantispam"=>1, "nclass"=>"","nshowtemplate"=>"","felements"=>array(
		array("fname"=>"Имя","ftype"=>1,"ffieldtype"=>"input","fdefault"=>""),
		array("fname"=>"Email","ftype"=>1,"ffieldtype"=>"email","fdefault"=>""),
		array("fname"=>"Текст","ftype"=>1,"ffieldtype"=>"textarea","fdefault"=>"")
	))
);
# .......................................................
class cms_node_feedback_ext extends cms_node_text{
	var $virtual_inself = false;
	var $identificator = 99;
	var $editable = true;
	var $config = true;
	var $statuses = array();
	# ... конструктор узла
	function & cms_node_feedback_ext($data){
		parent::cms_node_text($data);
		return $this;
	} 	
# ... обработчик событий узла (если событие захвачено то возврщаем истину)
	function handler(& $root, $event){
		global $access,$cms_alert,$template,$sql,$CMS_ALERTS,$CMS_LAST_MODIFIED,$log_id,$node_extends_vars,$current_node_extends_vars;
		$struct_bits = array(
				1=>array("name"=>"required","title"=>"обязательно для заполнения"),
				2=>array("name"=>"adminonly","title"=>"только для администратора")
		);
		$struct_bitsReserved = 5;
		$struct_types = array(
			array("name"=>"input","title"=>"строка текста"),
			array("name"=>"date","title"=>"дата"),
			array("name"=>"email","title"=>"email адрес"),
			array("name"=>"textarea","title"=>"текст"),
			array("name"=>"checkbox","title"=>"флажок"),
			array("name"=>"select","title"=>"поле со списком"),
			array("name"=>"file","title"=>"загрузка файла"),
			array("name"=>"hr","title"=>"горизонтальный разделитель"),
			array("name"=>"text","title"=>"статичный текст"),
			array("name"=>"column","title"=>"разделитель колонок"),
		);
		
		if($event != ''){
			if(parent::handler($root, $event)){ // если событие перехватил родительский класс
				return true;
			}
		}else{
			$this->load();
			# при такой последовательности сбрасываются расширенные настройки для показа, нужно ещё раз по конфигу пробежаться
			foreach($node_extends_vars as $key => $val){
				if(isset($this->config->data[$key])) $this->config->data[$key] = $val;
			}
		}
			if($access->status!=ACCESS_ADMIN && !$this->config->data["nantispam"]){
				include_once CMS_CLASSES_PATH."cms.antispam.php";
				$AS = new AntiSpam(CLIENT_OBJECTS_PATH.$this->id."/");
			}
			switch($event):
				case "edit": // "вызов шаблона редактирования узла"
					$struct = & $root->create("cms_node_content");
					$struct->set_file(cms_files_check("feedback_ext.edit"));
					$struct->set($this->data);
					if(!empty($this->config->data['nmap'])) $this->config->data['nmap'] = str2html($this->config->data['nmap']);
					$struct->set($this->config->data);
					$struct->setFromBranch();
					for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
						if(!empty($this->config->data["felements"][$i]["fvalue"])){
							$this->config->data["felements"][$i]["fvalue"] *= 1; 
							$this->config->data["felements"][$i]["fdefault"] = str_replace("\r\n","\\n",$this->config->data["felements"][$i]["fdefault"]);
						}
						$struct_data = & $struct->create("form_data");
						$struct_data->set($this->config->data["felements"][$i]);
						$struct_data->setFromBranch();
					}
					$struct->set("bitsReserved",$struct_bitsReserved);

					foreach($struct_types as $type => $values){
						$available_types = & $struct->create("available_types",$values);
					}
					foreach($struct_bits as $type => $values){
						$preavailable_types = & $struct->create("preavailable_types",$values);
						$preavailable_types->set("type",$type);
					}
					return false;
				break;
				case "_entry.edit": // "редактирование записи вопроса"
					$eid = $_GET["eid"]*1;
					if(!$eid || !$this->entry_node->check($this->id,$eid)){
						$cms_alert->set("er","form.entry.edit"); 
						$cms_alert->redirect($access->self.$this->get_full_path());
					}
					$this->entry_node->set($this->id,$eid,false);
					$edate = explode(".", htmlspecialchars($_POST['edate']));
					
					$edate = mktime(0,0,0, $edate[1], $edate[0], $edate[2]);
					
					$sql->send("UPDATE ".CMS_PREFIX."_node_feedback SET etype='".$this->entry_node->data["etype"]."',epublished='".$this->entry_node->data["epublished"]."', edate='".$edate."' WHERE eid=".$eid);
					if($this->entry_node->data["epublished"]==0){
						cms_tree("indexes",$this->id.":".$eid);	
					}else{
						# ... Вместо переиндексации - чистка кэша и удаление
						$sql->send("DELETE FROM ".CMS_PREFIX."_indexes WHERE sid=".$this->id." AND entryid='".$eid."'");
						clear_cash();
					}
					update_logs($log_id,$this->id,$eid);
					$cms_alert->set("ok","form.entry.edit"); 
					$cms_alert->redirect($access->self.$this->get_full_path());
					return true;
				break;
				case "_entry.remove": // "удалить записm"
					$eid = $_GET["eid"]*1;
					if(!$eid || !$this->entry_node->check($this->id,$eid)){ 
						$cms_alert->set("er","form.entry.remove"); 
						$cms_alert->redirect($access->self.$this->get_full_path());
					}
					$sql->send("INSERT INTO ".CMS_PREFIX."_garbage_node_feedback SELECT * FROM ".CMS_PREFIX."_node_feedback WHERE eid=".$eid);
					$sql->send("DELETE FROM ".CMS_PREFIX."_node_feedback WHERE eid=".$eid);
					$this->entry_node->get($this->id,$eid);
					for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
						if($this->config->data["felements"][$i]["ftype"]==8 ){
							$file = substr($this->entry_node->data["udata_".$i],strlen($access->base));
							if(is_file($file)) @rename($file,$file.".delete");
						}
					}
					$this->entry_node->remove($this->id,$eid);
					# ... Вместо переиндексации - чистка кэша и удаление
					$sql->send("DELETE FROM ".CMS_PREFIX."_indexes WHERE sid=".$this->id." AND entryid='".$eid."'");
					clear_cash();
					update_logs($log_id,$this->id,$eid);

					$cms_alert->set("ok","form.entry.remove"); 
					$cms_alert->redirect($access->self.$this->get_full_path());
					return true;
				break;
				case "_entry.add": // событие добавление записи
					$fd = fopen("/home/implant52/public_html/log.txt", 'a') or die("не удалось открыть файл");
					foreach($_POST as $key => $val){
						fwrite($fd, $key . ' ' . $val . "\n");
					}
					fclose($fd);

					$err = false;
					$arErr = array();

                    define("SECRET_KEY", "6LffryAqAAAAAEs1IR79_odI-5oEebmnqE5pljY7");
                    $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$_POST["token"]}");
                    $Return = json_decode($Response, true);
                    
                    if ($Return["success"] === false) {
                    	$arErr[] = "Вы робот!";
                    }
					//$_SESSION['cap'][$this->id] = '222';
					//print_r($AS->check($_POST['asword'],$this->id));
					if($access->status==ACCESS_ADMIN || $this->config->data["nantispam"] || (isset($AS) && empty($arErr) && $AS->check($_POST['asword'],$this->id))){
						$fd = fopen("/home/implant52/public_html/log.txt", 'a') or die("не удалось открыть файл");
						fwrite($fd, "access :" . "true" . "\n");
						fclose($fd);
						$formsend = "";
						include_once CMS_BASE."scripts/mail.php";
						# .........................................................
						$mailstructure = new structure();
						$mailroot = & $mailstructure->create('cms_mail_texts');

						$mailroot->set_file(cms_files_check("feedback_ext.mail"));
						$mailtext = & $mailroot->create("form_send");
						$mailtext->set($this->data);
						$mailtext->set('udate',int2date(time()));
						$mailtext->set($root->sdata);
						$data = str2save($_POST,true);
						$m = new Mail("u","html");
						$m->from(CMS_SYSTEM_MAIL,CLIENT_SITE_TITLE);
						$m->subj($this->config->data["flettertitle"]);
						for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
							$mailrows = & $mailtext->create("form_rows");
							$type = $this->config->data["felements"][$i]["ffieldtype"];

							if($type=="hr"){
								$mailrow = & $mailrows->create("form_hr");
							}elseif($type=="text"){
								$mailrow = & $mailrows->create("form_text",str2html($this->config->data["felements"][$i]));
							}else{
								if($type=="file"){
									if($file = file_upload("udata_".$i,CLIENT_OBJECTS_PATH.$this->id."/")){
										$m->attach_file($file);
										$_POST["udata_".$i] = $access->base.$file;
										$data["udata_".$i] = str2save($_POST["udata_".$i],true);
									}
								}
								if(!empty($data["udata_".$i])){
									$mailrow = & $mailrows->create("form_row",str2html($this->config->data["felements"][$i]));
									$mailrow->set("fvalue",str2html($data["udata_".$i],false,true,true));
								}
								if (!empty($data["udata_".$i]) && $type=="checkbox"){
									$mailrow = & $mailrows->create("form_row",str2html($this->config->data["felements"][$i]));
									$mailrow->set("fvalue","да");
								}
							}
						}
						# ... выбор пользователя
						if(!empty($_SESSION['cms_user']['uid'])){
							$gr = cms_tree("getbyid",$_SESSION['cms_user']['sid']);
							$user = array("user_id"=>$_SESSION['cms_user']['uid'],"self"=>$access->base."admin/","user_path"=>$gr->get_full_path(),"user_group"=>$gr->data['stitle']);
							$user_page = & $mailtext->create("user_page",$user);
							
							$gr_extended_vars = cms_get_node_extended_vars($_SESSION['cms_user']['sid']);
							$this_extended_vars = cms_get_node_extended_vars($this->id);

							if(!empty($gr_extended_vars['for_confirmation']) && $gr_extended_vars['for_confirmation']==$this->id && !empty($this_extended_vars['waiting_for_confirmation']) && $this_extended_vars['waiting_for_confirmation']==1){
								$query  = "UPDATE ".CMS_PREFIX."_node_users set utype=3 WHERE uid='".$_SESSION['cms_user']['uid']."'";
								$sql->send($query);
								$_SESSION['cms_user']['utype'] = 3;
							}
							$user_page->set($_SESSION['cms_user']);
							$user_page->set('utype', $gr->statuses[$_SESSION['cms_user']['utype']]);

							$query  = "SELECT * FROM ".CMS_PREFIX."_node_users_view_".$gr->id." WHERE uid=".$_SESSION['cms_user']['uid']."";
							$res = $sql->send($query);
							if($data = $sql->get()){
								if($gr->config->data["gfield"]>0){
									for($i=0;$i<min($gr->config->data["gfield"],sizeof($gr->config->data["felements"]));$i++){
										$nosaved = $gr->config->data["felements"][$i]['ftype'] & 8;
										if($nosaved!=0) continue;
										if($gr->config->data["felements"][$i]["ftype"]&2) continue;

										$struct_node_items = & $user_page->create("form_elements");
										
										$type = $gr->config->data["felements"][$i]["ffieldtype"];
										if($type=="hr" || $type=="text"){
											$struct_data = & $struct_node_items->create("form_simple");
										}else{
											$struct_data = & $struct_node_items->create("form_element");
										}
										$struct_data->set($gr->config->data["felements"][$i]);
										$struct_data->setFromBranch();

										$struct_element = & $struct_data->create("type_".$type);
										$struct_element->setFromBranch($struct_data->id());
										$struct_element->set("fvalue",$data["udata_".$i]);

										switch($type){
											case "checkbox":
												$struct_element->set("fvalue",$data["udata_".$i]==1 ? "да" : "нет");
											break;
											case "select":
											case "input":
											case "date":
											case "textarea":
											case "email":
												# ...
											break;
										}
									}
								}
							}
						}
						# ... /выбор пользователя

						$m->text($mailroot->get($template));
						if($array = explode(";",$this->config->data["fmail"])){
							
							for($j=0;$j<sizeof($array);$j++){
								$array[$j]=trim($array[$j]);
								if($array[$j]!=""){
									$m->to($array[$j]);	
									$m->send();
								}
							}
						}
						if($this->config->data["ntype"]!=2){ # 2 - только email, без хранения
							# ... добавление в базу
							$sql->send("INSERT INTO ".CMS_PREFIX."_node_feedback SET ".(!empty($_SESSION['cms_user']['uid']) ? "uid='".intval($_SESSION['cms_user']['uid'])."',gid='".intval($_SESSION['cms_user']['sid'])."'," : "")."edate='".$this->entry_node->sdata["edate"]."',epublished='".($this->config->data["ntype"]==1 ? 1: 0)."',sid=".$this->id);
							$eid = $sql->get_id();
							$sql->send("UPDATE ".CMS_PREFIX."_node_feedback SET ereg='".($this->config->data["fprefreg"].$eid)."' WHERE eid=".$eid);
							update_logs($log_id,$this->id,$eid);
							$this->entry_node->set($this->id,$eid);
							if($this->config->data["ntype"]==0) cms_tree("indexes",$this->id.":".$eid);	
						}
						$formsend = "?formsend=1";
						$cms_alert->set("ok","form.entry.add"); 
					}else{
						$formsend = "?formerror=1";
						$err = true;
						$cms_alert->set("er","form.antispam"); 
						$_SESSION["form".$this->id] = $_POST;
					}
					if($this->config->data["ftarget"]>0){
						$trgt = cms_tree("getbyid",$this->config->data["ftarget"]);
						$redirect_url = $access->self.$trgt->get_full_path();
					}else{
						$redirect_url = $access->self.$this->get_full_path();
					}
					if( $this->config->data["fmethod"]==1){
						if($access->status!=ACCESS_ADMIN && $this->config->data["nantispam"] === 1){
							$imageAntiSpam = $this->recaptcha($AS);
						}else{
							$imageAntiSpam = '';
						}
						
						if($err){
								
							echo '{"status":"error","sid":"'.$this->id.'","anti":"'.$imageAntiSpam.'","message":"'.$CMS_ALERTS["er"]["form.antispam"].'"}';	
							//echo '{"status":"error","sid":"'.$this->id.'","anti":"'.$imageAntiSpam.'","message":"'.$CMS_ALERTS["er"]["form.antispam"].'<pre style="display:none;">'.$this->id. print_r($_SESSION, true).'</pre>"}';		
						}else{
							if ($this->config->data["fresult"]==0) $cms_alert->set_sess(); 
							echo '{"status":"ok","target":"'.$this->config->data["ftarget"].'","sname":"'.$this->data["sname"].'","fwidth":"'.$this->config->data["fwidth"].'","sid":"'.$this->id.'","link":"'.$this->config->data["frequest_link"].'","show":"'.$this->config->data["nshow"].'","anti":"'.$imageAntiSpam.'","alert":"'.$this->config->data["fresult"].'","message":"'.$CMS_ALERTS["ok"]["form.entry.add"].'","url":"'.$redirect_url.($this->config->data["ftarget"]==0?"":$formsend).'"}';	
						}
						exit();
						break;
					}else{
						$cms_alert->redirect($redirect_url.$formsend);
						return true;
					}
				break;
				case "entry.edit": // "редактирование записи"
					$eid = $_GET["eid"]*1;
					if(!$eid || !$this->entry_node->check($this->id,$eid)){			
						$cms_alert->set("er","form.entry.edit"); 
						$cms_alert->redirect($access->self.$this->get_full_path());
					}
					$this->entry_node->get($this->id,$eid);

					$this->entry_node->data["eid"] = $eid;
					$struct = & $root->create("cms_node_content");
					$struct->set_file(cms_files_check("feedback_ext.entry.edit"));

					if(!empty($this->statuses)){
						foreach($this->statuses as $k=>$status){
							$struct->create("stat",array("stat_name"=>$status,"stat_val"=>$k));
						}
					}
						
					if(is_int(+$this->entry_node->data["edate"])) {
						$this->entry_node->data["edate"] = int2date($this->entry_node->data["edate"],CLIENT_DATE_FORMAT);
					}
					
					$struct->create("edit_date", array('format' => $this->entry_node->data["edate"]));

	

					
					$struct->set($this->data);
					$struct->set($this->config->data);
					$struct->set($this->entry_node->data);
					$struct->setFromBranch();
					$tinymce = & $struct->create("tinymce");
					$tinymce->set_file(cms_files_check("tinymce"));
					$tinymce->setFromBranch();
					for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
						$struct_data = & $struct->create("form_element");
						$struct_data->set($this->config->data["felements"][$i]);
						$struct_data->set("fsort",$i);
						$struct_data->setFromBranch();
						
						$type = $this->config->data["felements"][$i]["ffieldtype"];
						$required = $this->config->data["felements"][$i]["ftype"]&1;
						$struct_element = & $struct_data->create("type_".$type);
						$struct_element->setFromBranch($struct_data->id());
						$struct_element->set("fvalue",str2html($this->entry_node->data["udata_".$i]));
						switch($type){
							case "select":
								if($array = explode(";",$this->config->data["felements"][$i]["fdefault"])){
									for($j=0;$j<sizeof($array);$j++){
										$array[$j]=trim($array[$j]);
										if($array[$j]!=""){
											$struct_element_item = & $struct_element->create("type_select_item");
											$struct_element_item->set("fvalue",$array[$j]);
											if($array[$j]==$this->entry_node->data["udata_".$i]) $struct_element_item->set("selected","selected");
											$struct_element_item->setFromBranch($struct_data->id());
										}
									}
								}	
							break;
							case "checkbox":
								if($required) {
									$check_data = & $struct->create("check_data",array("check_type"=>3));
									$check_data->setFromBranch($struct_data->id());
								} else {
									if($this->config->data["felements"][$i]["fdefault"]==$this->entry_node->data["udata_".$i])
										$struct_element->set("selected","checked");
								}
							break;
							case "input":
							case "date":
							case "textarea":
								if($required) {
									$check_data = & $struct->create("check_data",array("check_type"=>-1));
									$check_data->setFromBranch($struct_data->id());
								}
							break;
							case "email":
								if($required) {
									$check_data = & $struct->create("check_data",array("check_type"=>1));
									$check_data->setFromBranch($struct_data->id());
								}
							break;
						}
					}
					return false;
				break;
				case "entry.show": // "показ записи"
					$eid = $_GET["eid"]*1;
					if(!$eid || !$this->entry_node->check($this->id,$eid)){			
						$cms_alert->set("er","form.entry.show"); 
						$cms_alert->redirect($access->self.$this->get_full_path());
					}
					$this->entry_node->get($this->id,$eid);
					$this->entry_node->data["eid"] = $eid;
					if($access->status!=ACCESS_ADMIN){
						$CMS_LAST_MODIFIED = max($CMS_LAST_MODIFIED,$data["edate"]);
					}

					$this->entry_node->data["edate"] = int2date($data["edate"],CLIENT_DATE_FORMAT);
					$this->entry_node->data["edate_d"] = int2date($data["edate"],defined("CLIENT_DATE_FORMAT_D")?CLIENT_DATE_FORMAT_D:"d");
					$this->entry_node->data["edate_m"] = int2date($data["edate"],defined("CLIENT_DATE_FORMAT_M")?CLIENT_DATE_FORMAT_M:"m");
					$this->entry_node->data["edate_y"] = int2date($data["edate"],defined("CLIENT_DATE_FORMAT_Y")?CLIENT_DATE_FORMAT_Y:"Y");	


					$struct = & $root->create("cms_node_content");
					$struct->set_file(cms_files_check("feedback_ext.entry".($this->config->data["nshowtemplate"]!=""?".".$this->config->data["nshowtemplate"]:"").".show"));

					$struct->set($this->data);
					$struct->set($this->config->data);
					$struct->set($this->entry_node->data);
					$struct->setFromBranch();
					if($access->status==ACCESS_ADMIN || !$this->config->data["ndate"]){
						$detail_node = & $struct->create("cms_entry_date");
						$detail_node->setFromBranch($struct->id());
					}

					for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
						$type = $this->config->data["felements"][$i]["ffieldtype"];
						if($type=="hr"){
							$cms_entry_fields = & $struct->create("cms_entry_fields");
							$struct_node_item = & $cms_entry_fields->create("cms_entry_hr");
						}elseif($type=="text"){
							$cms_entry_fields = & $struct->create("cms_entry_fields");
							$struct_node_item = & $cms_entry_fields->create("cms_entry_text",str2html($this->config->data["felements"][$i]));
						}else{
							if(strlen($this->entry_node->data["udata_".$i])>0 && ($access->status==ACCESS_ADMIN || !$this->config->data["nmail"] || $this->config->data["felements"][$i]["ffieldtype"]!="email")){
								$cms_entry_fields = & $struct->create("cms_entry_fields");
								$struct_node_item = & $cms_entry_fields->create("cms_entry_field",str2html($this->config->data["felements"][$i]));
								switch($type){
									case "textarea":
										$struct_node_item->set("fvalue",str2html($this->entry_node->data["udata_".$i]));
									break;
									default:
										$struct_node_item->set("fvalue",str2html($this->entry_node->data["udata_".$i],false,true,true));
								}
							}
						}
					}
					return false;
				break;
				case "_recaptcha": // обновление антикапчи
					$imageAntiSpam = $this->recaptcha($AS);
					echo $imageAntiSpam;	
					exit();
				break;
				default:// "вызов шаблона показа узла"
					$tmp = cms_content_check("feedback_ext.show",$this->config->data["nshowtemplate"]);
					if(trim($this->config->data["nblocks"]) == '') $this->config->data["nblocks"] = '0';
					$blocks = explode(',',$this->config->data["nblocks"]);
					/*			Номера блоков:<br>
								1 - форма
								2 - текст
								3 - карта
					*/
					$this->config->data['nmap'] = str2html($this->config->data['nmap']);
					
					foreach($blocks as $key => $val){
						switch($val):
							case 0: #старые формы - парент, потом проваливаемся на форму
								parent::handler($root, $event);
							case 1: 
								$struct = & $root->create("cms_node_content");
								$struct->set_file($tmp);
								$struct->set($this->data);
								$struct->set($this->config->data);
								$struct->setFromBranch();
								if(cms_permission($this,"entries.list") && ($access->status==ACCESS_ADMIN || $this->config->data["ntype"]!=3 || ($this->config->data["ntype"]==3 && !empty($_SESSION['cms_user'])))){
									# ... запрос на выбор дочерних узлов
									$query_where = "sid = ".$this->id." ".(($access->status==ACCESS_ADMIN)?"":"AND epublished=0 ");
									if($this->config->data["ntype"]==3 && $access->status!=ACCESS_ADMIN ){ # только свои записи
										$query_where .= " AND uid='".$_SESSION['cms_user']["uid"]."'";
									}
									# ... выбор страниц
									$current = cms_pages($struct,"SELECT COUNT(eid) as count FROM ".CMS_PREFIX."_node_feedback WHERE ".$query_where,$this->config->data["natpage"],!$this->config->data["npages"]);
									# ... выбор узлов
									$query  = "SELECT * FROM ".CMS_PREFIX."_node_feedback WHERE ".$query_where." ";
									$query .= "ORDER BY edate ".(($this->config->data["nsort"])?"DESC":"ASC")." ";
									if($this->config->data["natpage"]>0) $query .= " LIMIT ".($current*$this->config->data["natpage"]).",".$this->config->data["natpage"]."";
									$res = $sql->send($query);
									if($sql->num($res)>0){
										$cms_entries = & $struct->create("cms_entries");
										$cms_entry_headers = & $cms_entries->create("cms_entry_headers");

										$fsize = sizeof($this->config->data["felements"]);
										$fsize = ($this->config->data["nfields"]==0)?$fsize:min($this->config->data["nfields"],$fsize);

										if($access->status==ACCESS_ADMIN || !$this->config->data["ndate"]){
											$cms_entry_headers->create("cms_entry_date1");
										}

										for($i=1;$i<$fsize;$i++){
											if($access->status!=ACCESS_ADMIN && $this->config->data["nmail"]==1 && $this->config->data["felements"][$i]["ffieldtype"]=="email") continue;
											$type = $this->config->data["felements"][$i]["ffieldtype"];
											if($type!="hr" && $type!="text" && $type!="column"){
												$cms_entry_header = & $cms_entry_headers->create("cms_entry_header",str2html($this->config->data["felements"][$i]));
											}
										}

										while($data = $sql->get($res)){
											$this->entry_node->get($this->id,$data["eid"]);
											if($access->status!=ACCESS_ADMIN){
												$CMS_LAST_MODIFIED = max($CMS_LAST_MODIFIED,$data["edate"]);
											}
											$this->entry_node->data["edate"] = int2date($data["edate"],CLIENT_DATE_FORMAT);
											$this->entry_node->data["edate_d"] = int2date($data["edate"],defined("CLIENT_DATE_FORMAT_D")?CLIENT_DATE_FORMAT_D:"d");
											$this->entry_node->data["edate_m"] = int2date($data["edate"],defined("CLIENT_DATE_FORMAT_M")?CLIENT_DATE_FORMAT_M:"m");
											$this->entry_node->data["edate_y"] = int2date($data["edate"],defined("CLIENT_DATE_FORMAT_Y")?CLIENT_DATE_FORMAT_Y:"Y");	

											$this->entry_node->data["eid"] = $data["eid"];
											$this->entry_node->data["ereg"] = $data["ereg"];
											$this->entry_node->data["uid"] = $data["uid"];
											$this->entry_node->data["etype"] = $this->statuses[$data["etype"]];
		
											$this->entry_node->data["epublished"] = $data["epublished"];
											$this->entry_node->data["epublished"] = $this->entry_node->data["epublished"]==0?"Опубликован":"Скрыт";
											$gr = cms_tree("getbyid",$data['gid']);
											$this->entry_node->data["user_path"] = $gr->get_full_path();
											$this->entry_node->data["user_group"] = $gr->data['stitle'];
											
											
											$struct_node = & $cms_entries->create("cms_entry");
											$struct_node->set(str2html($this->entry_node->data));
											$struct_node->setFromBranch($struct->id());

											if(strlen($this->entry_node->data["udata_0"])>0 && ($access->status==ACCESS_ADMIN || !$this->config->data["nmail"] || $this->config->data["felements"][0]["ffieldtype"]!="email")){
												$struct_node->set("fvalue",str2html($this->entry_node->data["udata_0"],false,true,true));
											}	
											//$fsize = sizeof($this->config->data["felements"]);
											//$fsize = ($this->config->data["nfields"]==0)?$fsize:min($this->config->data["nfields"],$fsize);
											for($i=1;$i<$fsize;$i++){
												if($access->status!=ACCESS_ADMIN && $this->config->data["nmail"]==1 && $this->config->data["felements"][$i]["ffieldtype"]=="email") continue;
												$type = $this->config->data["felements"][$i]["ffieldtype"];
                        
												/*if($type=="hr"){
													$cms_entry_fields = & $struct_node->create("cms_entry_fields");
													$struct_node_item = & $cms_entry_fields->create("cms_entry_hr");
												}elseif($type=="text"){
													$cms_entry_fields = & $struct_node->create("cms_entry_fields");
													$struct_node_item = & $cms_entry_fields->create("cms_entry_text",str2html($this->config->data["felements"][$i]));
												}else{*/
											if($type!="hr" && $type!="text" && $type!="column"){

													//if(strlen($this->entry_node->data["udata_".$i])>0){
														$cms_entry_fields = & $struct_node->create("cms_entry_fields");
														$struct_node_item = & $cms_entry_fields->create("cms_entry_field",str2html($this->config->data["felements"][$i]));
														switch($type){
															case "textarea":
                                if($this->id == (22 || 27)) {
                                  $struct_node_item_text = & $struct_node->create("cms_node_item_text");
                                  $struct_node_item_text->set("value",str2html($this->entry_node->data["udata_".$i]));
                                } else {
                                  $struct_node_item->set("fvalue",str2html($this->entry_node->data["udata_".$i]));
                                }
																
															break;
															default:
																$struct_node_item->set("fvalue",str2html($this->entry_node->data["udata_".$i],false,true,true));
														}
													//}
												}
												if(!empty($_SESSION["form".$this->id])) $struct_node_item->set($_SESSION["form".$this->id]);
											}
											if($fsize!=sizeof($this->config->data["felements"])){
												$detail_node = & $struct_node->create("cms_entry_detail");
												$detail_node->setFromBranch($struct_node->id());
												$struct_node->create("cms_entry_detail1");
											}
											if($access->status==ACCESS_ADMIN || !$this->config->data["ndate"]){
												$detail_node = & $struct_node->create("cms_entry_date");
												$detail_node->setFromBranch($struct_node->id());
											}
											if($access->status==ACCESS_ADMIN){
												$struct_node->set("cms_class",($data["epublished"]==1)?"cms_hidden":"");
												$struct_node_admin = & $struct_node->create("cms_feedback_admin");
												$struct_node_admin->set_file(cms_files_check("feedback_ext.admin"));
												$struct_node_admin->setFromBranch($struct_node->id());
											}
										}
									}
								}


								if(!($this->config->data["nform2admin"]==0 && $access->status==ACCESS_ADMIN) && cms_permission($this,"_entry.add") && (!isset($_GET["formsend"]) || $this->config->data["nshow"]==1)){
									$struct_add = & $struct->create("cms_entry_add");
									if($this->config->data["fmethod"]==1){
										$ajax_content = & $struct->create("ajax_content", array("sid"=>$this->id));
										$ajax_content->set($this->config->data);
										$ajax_content->set("sname",$this->data["sname"]);
										$ajax_content->setFromBranch($struct->id());
										$ajax_content->set_file(cms_files_check("feedback_ext.ajax"));
										if($this->config->data["fresult"]==2){
											$ajax_gritter = & $ajax_content->create("ajax_gritter", array("base"=>$access->base));
										}
									}
									if($access->status!=ACCESS_ADMIN && $this->config->data["nantispam"]==0){
										//if ($_SESSION['cap'][$this->id] == '1') {
										/* if (false) {
											$_SESSION['cap'][$this->id] = '222';
										} else { */
											//$_SESSION['cap'][$this->id] = '1';
											$imageAntiSpam = $AS->create(4,$this->id);
											$cms_antispam = & $struct_add->create("cms_antispam");
											$cms_antispam->setFromBranch($struct->id());
											$cms_antispam->set("imageAntiSpam",$imageAntiSpam);
											if($this->config->data["nreantispam"]==0){
												$cms_reantispam = & $cms_antispam->create("cms_reantispam");
												$cms_reantispam->setFromBranch($struct->id());
											}
										//}
									}
									$form_column = false;
									$column = 1;
									for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
										if($access->status!=ACCESS_ADMIN && ($this->config->data["felements"][$i]["ftype"]&2)) continue;
										$type = $this->config->data["felements"][$i]["ffieldtype"];
										if(!$form_column || $type=="column"){
											$form_column = & $struct_add->create("form_column");
											$form_column->set("num", $column++);
										}
										$struct_node_items = & $form_column->create("form_elements");										
										if($type=="hr" || $type=="text"){
											$struct_data = & $struct_node_items->create("form_simple");
										}else{
											$struct_data = & $struct_node_items->create("form_element");
										}
										$struct_data->set($this->config->data["felements"][$i]);
										$struct_data->set("fsort",$i);
										$struct_data->set("sid",$this->id);
										$struct_data->setFromBranch();



										$required = $this->config->data["felements"][$i]["ftype"]&1;
										$struct_element = & $struct_data->create("type_".$type);
										$struct_element->setFromBranch($struct_data->id());
										$required = $this->config->data["felements"][$i]["ftype"]&1;
										if($required==1){
											$struct_data->create("required");
											$struct_element->create("required");
										}
										switch($type){
											case "select":
												if($array = explode(";",$this->config->data["felements"][$i]["fdefault"])){
													for($j=0;$j<sizeof($array);$j++){
														$array[$j]=trim($array[$j]);
														if($array[$j]!=""){
															$struct_element_item = & $struct_element->create("type_select_item");
															$struct_element_item->set("fvalue",$array[$j]);
															$struct_element_item->setFromBranch($struct_data->id());
														}
													}
												}	
											break;
											case "checkbox":
												if($required) {
													$check_data = & $struct_add->create("check_data",array("check_type"=>3));
													$check_data->setFromBranch($struct_data->id());
												} else {
													if(!empty($this->entry_node->data["udata_".$i]) && $this->config->data["felements"][$i]["fdefault"]==$this->entry_node->data["udata_".$i])
														$struct_element->set("selected","checked");
												}
											break;
											case "input":
											case "date":
											case "textarea":
												if($required) {
													$check_data = & $struct_add->create("check_data",array("check_type"=>-1));
													$check_data->setFromBranch($struct_data->id());
												}
											break;
											case "email":
												if($required) {
													$check_data = & $struct_add->create("check_data",array("check_type"=>1));
													$check_data->setFromBranch($struct_data->id());
												}
											break;
										}
									}
									$struct_add->setFromBranch($struct->id());
								}else{
									if(!cms_permission($this,$event)){
										if($event=="show" || $event==""){
											if($this->permission_special){
												$_SESSION["cms_special"] = false;
												$struct = & $root->create("cms_node_content");
												$struct->set_file(cms_files_check("node.login"));
												$struct->set($this->data);
												return true; 
											}else{
												$cms_alert->set("er","node.forbid");
												$cms_alert->set_text(file2str(CLIENT_OBJECTS_PATH.$this->id."/forbid.text.html"));
											}
										}else{
											$cms_alert->set("er","node.action.forbid");
										}
										return true;
									}
								}
							break;
							case 2: #текст
								$struct = & $root->create("cms_node_content");
								$struct->set_file($tmp);
								$cms_text = & $struct->create("cms_text");
								$cms_text->set("fpath",$this->data["fpath"]);
								$cms_text->set("parentpath",$this->parent->data["fpath"]);
								$cms_text->set($this->data);
								$cms_text->setFromBranch();
							break;
							case 3: #карта
								$this->config->data["nmap"] = str2html($this->config->data["nmap"]);
								$struct = & $root->create("cms_node_content");
								$struct->set_file($tmp);
								$cms_text = & $struct->create("cms_map");
								$cms_text->set($this->data);
								$cms_text->set("nmap",$this->config->data['nmap']);
								$cms_text->set("fpath",$this->data["fpath"]);
								$cms_text->set("parentpath",$this->parent->data["fpath"]);
								$cms_text->setFromBranch();

							break;
						endswitch;
					}


					//parent::handler($root, $event);
					return false;
				break;
			endswitch;
			return false;


	} 

# ... строка поиска узла
	function search($with_entries=false){
		global $sql;
		$strings = array();		
		$strings[0] = (parent::search());
		if($with_entries){
			$query  = "SELECT eid FROM ".CMS_PREFIX."_node_feedback WHERE sid = ".$this->id." AND epublished=0";
			$res = $sql->send($query);
			while($data = $sql->get($res)){
				$strings[$data["eid"]] = $this->entry_search($data["eid"]);
			}
		}
		return $strings;
	} 
# ... строка поиска элемента
	function entry_search($entryid=0){
		global $sql;
		$entry_string = "";
		if($entryid*1>0){
			$this->entry_node->get($this->id,$entryid);
			$this->entry_node->data["eid"] = $entryid;
			if(true){
				for($i=0;$i<sizeof($this->config->data["felements"]);$i++){
					if(strlen($this->entry_node->data["udata_".$i])>0 && ($this->config->data["felements"][$i]["ffieldtype"]=="textarea" || $this->config->data["felements"][$i]["ffieldtype"]=="text")){
						$entry_string .= " ".$this->entry_node->data["udata_".$i];
					}
				}
			}
		}
		return $entry_string;
	} 
# ... загрузка элемента для поиска
	function entry_load($entryid=0){
		global $sql;
		$data = array();
		if($entryid*1>0){
			$query  = "SELECT edate FROM ".CMS_PREFIX."_node_feedback WHERE eid = ".$entryid."";
			$res = $sql->send($query);
			if($data = $sql->get($res)){
				$data["sname"] = int2date($data["edate"],CLIENT_DATE_FORMAT);
				$data["fpath"] = $this->get_full_path();
				$data["entrypath"] = "eid=".$entryid."&a=entry.show&";
			}
		}
		return $data;
	} 
# ... перезагрузка капчи
	function recaptcha(& $AS){
		$imageAntiSpam = $AS->create(4,$this->id);
		$imageAntiSpam = explode('//',$imageAntiSpam);
		$imageAntiSpam = $imageAntiSpam[1];
		$imageAntiSpam = explode('.jpg',$imageAntiSpam);
		$imageAntiSpam = 'https://'.$imageAntiSpam[0].'.jpg';
		return $imageAntiSpam;
	} 
# ... загрузить данные узла
	function load(){
		global $sql,$current_node_extends_vars,$CMS_NODE_TYPES;
		parent::load();
		$this->config->get($this->id);
		# ... смотрим, нет ли переопределния 	данных для конфига
		$current_node_extends_vars = cms_node_extended_vars($this->id);
		foreach ($current_node_extends_vars as $key => $value) {
			if (array_key_exists($key,$CMS_NODE_TYPES[$this->identificator]["_config"])) {
				$this->config->data[$key] = $value;
			}
		}
		# ... 
		$entry_config_array = array("edate"=>time(), "etype"=>0,"epublished"=>$this->config->data["ntype"]);
		for($i=0;$i<sizeof($this->config->data["felements"]);$i++)	$entry_config_array["udata_".$i] = "";
		$this->statuses = explode(';',trim($this->config->data['fstatuses']));
		$this->entry_node = new cms_config($entry_config_array); 
	} 
# ... установить данные узла
	function set($data){
		global $sql;
		parent::set($data);
		$this->config->set($this->id);
	} 
# ... удалить данный узла
	function remove(){
		global $sql;
		parent::remove();
		$sql->send("INSERT INTO ".CMS_PREFIX."_garbage_node_feedback SELECT * FROM ".CMS_PREFIX."_node_feedback WHERE sid=".$this->id."");
		$sql->send("DELETE FROM ".CMS_PREFIX."_node_feedback WHERE sid=".$this->id."");
	} 
# ... показать информацию о записи
	function get_entry_info(& $root,$entryid,$operation){
		global $sql,$access;
		$query1  = "SELECT edate as entryname, 'eid' as  entrynameid, eid as entryid FROM ".CMS_PREFIX.(strpos($operation,"remove")!==false?"_garbage":"")."_node_feedback WHERE sid = '".($this->id)."' AND eid='".$entryid."'";
		$res1 = $sql->send($query1);
		while($data1 = $sql->get($res1)){
			$data1["entryname"] = "Запись от ".int2date($data1["entryname"]);
			$cms_entry_more = & $root->create("cms_entry_more",$data1);
			$cms_entry_more->set($data1);
			$cms_entry_more->set($this->data);
			if(strpos($operation,"remove")===false){
				$cms_entry_more_ref = & $cms_entry_more->create("cms_entry_more_ref");
				$cms_entry_more_ref->set($data1);
				$cms_entry_more_ref->set($this->data);
				$cms_entry_more_ref->set("self",$access->self);
			}else{
				$cms_entry_more->create("cms_entry_more_noref");
			}
		}
	} 
}
# .......................................................
$query = "CREATE TABLE IF NOT EXISTS ".CMS_PREFIX."_node_feedback(";
$query .= "eid int(10) unsigned NOT NULL auto_increment,";
$query .= "sid int(11) NOT NULL default '0',";
$query .= "uid int(11) NOT NULL default '0',";
$query .= "gid int(11) NOT NULL default '0',";
$query .= "etype int(11) NOT NULL default '0',";
$query .= "ereg varchar(50) NOT NULL default '',";
$query .= "epublished tinyint(3) NOT NULL default '0',";
$query .= "edate int(11) unsigned NOT NULL default '0',";
$query .= "PRIMARY KEY (eid),";
$query .= "KEY sid (sid),";
$query .= "KEY edate (edate)";
$query .= ")  ENGINE=MyISAM ";

$CMS_NODE_SQL_QUERY[] = $query;
# .......................................................
?>