<?php

	class CBBS extends CBBS_CORE
	{
		var $m_SET;
		var $m_CONFIG;
		var $m_bState;

		// for 보기
		var $DATA;

		function CBBS()
		{
			global $SCREEN;

			$this->m_CONFIG["BBS_LIST_DEC_BOTTOM"] = " ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━&nbsp;".$SCREEN->m_strCRLF;
		}

		function PROPERTY( $PROPERTY, $VALUE )
		{
			$this->m_CONFIG[$PROPERTY] = $VALUE;
		}
		
		function ARGUMENT( $SET="" )
		{
			if( ! $SET )
				$SET = $this->m_SET;
			
			//$setstr  = "?&TABLE={$SET["TABLE"]}";
			$setstr = "?";
			
			if( $SET["PAGE"]   )		$setstr .= "&PAGE={$SET["PAGE"]}";
			if( $SET["SEARCH"]   )		$setstr .= "&SEARCH={$SET["SEARCH"]}";
			if( $SET["SEARCH_FIELD"] )	$setstr .= "&SEARCH_FIELD={$SET["SEARCH_FIELD"]}";
			if( $SET["ORDER_FIELD"] )	$setstr .= "&ORDER_FIELD={$SET["ORDER_FIELD"]}";
			if( $SET["ORDER"] )			$setstr .= "&ORDER={$SET["ORDER"]}";

			return $setstr;
		}

		function MAKEMENU( $SET="" )
		{
			global $DATA, $DATA_name, $DATA_size, $DATA_tmp_name, $DATA_type;
			global $MENU;
			
			if( $SET ) {
				$this->m_SET = $SET;
			}
			
			// P 메뉴 EDIT
			$PREV = $PHP_SELF.$this->ARGUMENT();
			$MENU->EDIT( "P", "URL", $PREV ); 
			$MENU->EDIT( "ㅔ", "URL", $PREV ); 
			
			// 메뉴생성
			$L  = Array ( "CODE" => "L",  "NAME" => "L",
						  "URL" => $PHP_SELF.$this->ARGUMENT()."&MODE=".BBS_MODE_LIST,
						  "BOTTOM" => true,
						  "TIP" => "글목록" );

			$MENU->ITEMADD( $L );

			$L["CODE"] = "ㅣ";
			$L["BOTTOM"] = false;
			$MENU->ITEMADD( $L );
			

			// DATA값 검사
			$DATA[TABLE] = $SET[TABLE];

			if( trim($DATA[TABLE]) == "" ||
				trim($DATA[TITLE]) == "" ||
				trim($DATA[NAME]) == "" ||
				trim($DATA[PASSWORD]) == "" ) {

				$this->m_bState = false;
				return false;
			}
			
			$DATA["FILE_name"] = $DATA_name["FILE"];
			$DATA["FILE_size"] = $DATA_size["FILE"];
			$DATA["FILE_type"] = $DATA_type["FILE"];
			$DATA["FILE_tmp_name"] = $DATA_tmp_name["FILE"];
			
			// 요청화면에 따른 작업 처리
			if( $DATA[MODE] == BBS_MODE_WRITE ) {

				if( $SET["PASSWORD"] != "" ) {
					if( $SET["PASSWORD"] != $DATA["PASSWORD"] ) {
						$this->m_bState = "쓰기권한";		
						return false;
					}
				}
				
				$this->m_bState = $this->WRITE( $DATA );

			} else if( $DATA[MODE] == BBS_MODE_REPLY ) {

				if( $SET["PASSWORD"] != "" ) {
					if( $SET["PASSWORD"] != $DATA["PASSWORD"] ) {
						$this->m_bState = "쓰기권한";		
						return false;
					}
				}

				$this->m_bState = $this->WRITE( $DATA, BBS_WRITE_REPLY );

			} else if( $DATA[MODE] == BBS_MODE_EDIT ) {
				
				$this->m_bState = $this->EDIT( $DATA );
				
			}
			
		}

		function SHOW()
		{
			global $DATA, $SCREEN;

			$m_bState = "{$this->m_bState}";
			
			if( ($m_bState == "PASSWORD") ) {
				echo " 글 저장 실패 !!!";
				echo $SCREEN->m_strCRLF;
				echo $SCREEN->m_strCRLF;
				echo " 암호가 틀렸습니다.";	
				echo $SCREEN->m_strCRLF;
				echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
				return false;
			}
		
			if( $m_bState == "쓰기권한" ) {
				echo " 글 저장 실패 !!!";
				echo $SCREEN->m_strCRLF;
				echo $SCREEN->m_strCRLF;
				echo " 글쓰기가 허용된 패스워드가 아닙니다.";
				echo $SCREEN->m_strCRLF;
				echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
				return false;
			}
			
			if( ! $this->m_bState ) {
				echo " 글 저장 실패 !!!";
				echo $SCREEN->m_strCRLF;
				echo $SCREEN->m_strCRLF;
				echo " 필수 입력 항목이 제대로 입력되지 않았습니다.";
				echo $SCREEN->m_strCRLF;
				echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
				return false;
			}
			
			echo " 글저장 완료.";
			echo $SCREEN->m_strCRLF;
			echo $SCREEN->m_strCRLF;
			echo " 목록으로 돌아가려면 P를 누르세요.";
			echo $SCREEN->m_strCRLF;
			echo $this->m_CONFIG["BBS_LIST_DEC_BOTTOM"];
			return true;
		}
		
	}
	
?>
