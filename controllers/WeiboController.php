<?php
Yii::import('ext.sinaWeibo.SinaWeibo',true);

class WeiboController extends Controller
{  
	
	public function actionIndex(){
		$weiboService=new SinaWeibo(WB_AKEY, WB_SKEY);
		$code_url = $weiboService->getAuthorizeURL( WB_CALLBACK_URL );
		$_SESSION['back_url']=$this->createUrl('weibolist');
		echo '<a href="'.$code_url.'">点击授权</a>';
		
	}
	public function actionCallback(){
		$weiboService=new SinaWeibo(WB_AKEY, WB_SKEY);
		if (isset($_REQUEST['code'])) {
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = WB_CALLBACK_URL;
			try {
				$token = $weiboService->getAccessToken( 'code', $keys ) ;
			} catch (OAuthException $e) {
			}
		}
		
		if ($token) {
			$_SESSION['token'] = $token;
			setcookie( 'weibojs_'.$weiboService->client_id, http_build_query($token) );
			header( "refresh:3;url=".$_SESSION[back_url]);
			echo "<h1>认证已经通过，将会在3秒之后跳转到微博列表页面。如果没有，点击<a href=".$_SESSION['back_url'].">这里</a>。</h1>";exit;
			 
			
			
		} else {
		    echo '认证失败';
		}
	}
	public function actionWeibolist(){

		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$ms  = $c->home_timeline(); // done
		
		var_dump($ms);exit;
		$uid_get = $c->get_uid();
		$uid = $uid_get['uid'];
		$user_message = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		
				
	}
	
	
	
	
	
	
	
	
}