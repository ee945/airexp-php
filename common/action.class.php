<?php
//全局操作类：登录，退出，权限，提示，警告
class action extends mysql {

	/**
	 * 用户权限判断($uid, $shell, $m_id)
	 */

	public function Get_user_shell($uid, $shell) {
		$query = $this->select('exp_user', '*', '`id` = \'' . $uid . '\'');
		$us = is_array($row = $this->fetch_array($query));
		$shell = $us ? $shell == md5($row[name] . $row[pass] . "ee945") : FALSE;  //登录判断，md5验证（用户名+密码+"特征字符"）
		return $shell ? $row : NULL;
	} //end shell

	public function Get_user_shell_check($uid, $shell) {
		if ($row=$this->Get_user_shell($uid, $shell)) {
			return $row;
		} else {
		  $this->Get_admin_msg('login.php','请先登陆');
		}
	} //end shell
	//========================================


	/**
	 * 用户登陆超时时间(秒)
	 */
	public function Get_user_ontime($long = '3600') {
		$new_time = time();
		$onlinetime = $_SESSION[expontime];
		if ($new_time - $onlinetime > $long) {
			session_destroy();
            $this->Get_admin_msg('login.php','登录超时！');
			exit ();
		} else {
			$_SESSION[expontime] = time();
		}
	}

	/**
	 * 用户登陆
	 */
	public function Get_user_login($username, $password) {
		$username = str_replace(" ", "", $username);
		$query = $this->select('exp_user', '*', '`name` = \'' . $username . '\'');
		$us = is_array($row = $this->fetch_array($query));

		$ps = $us ? md5($password) == $row[pass] : FALSE;
		if ($ps) {
			$_SESSION[expid] = $row[id];  //登录用户id
            $_SESSION[expname] = $row[name];  //用户名
			$_SESSION[expshell] = md5($row[name] . $row[pass] . "ee945");  //用户shell
			$_SESSION[expontime] = @mktime();  //登录时间
            $_SESSION[expgrade] = $row[grade];  //用户操作权限（查插改删）
            $_SESSION[expaccess] = $row[access];  //用户模块访问权限（分单列表，统计报表，财务结算，用户管理等）
            $_SESSION[expnick] = $row[nick];  //用户昵称
            $_SESSION[expdept] = $row[dept];  //用户部门
            $_SESSION[exppg] = 20;  //默认（分页）每页显示条数
			$this->Get_admin_msg('index.php','登陆成功！','0');
		} else {
            session_destroy();
			$this->Get_admin_alert('密码或用户错误！');
		}
	}
	 /**
	  * 用户退出登陆
	  */
	public function Get_user_out() {
		session_destroy();
		$this->Get_admin_msg('login.php','退出成功！');
	}

   /**
    * 后台通用信息提示，操作成功后提示，随后跳转
    */
	public function Get_admin_msg($url, $show = '操作已成功！',$refreshtime="1") {
		$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml"><head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta http-equiv="refresh" content="' . $refreshtime . '; URL=' . $url . '" />
				<title>管理区域</title>
				</head>
				<body>
				<div id="man_zone">
				  <table width="30%" border="1" align="center"  cellpadding="3" cellspacing="0" class="table" style="margin-top:100px;">
				    <tr>
				      <th align="center" style="background:#f4f5eb">信息提示</th>
				    </tr>
				    <tr>
				      <td style="font-size:14px;"><p>' . $show . '<br />
				      ' . $refreshtime . '秒后返回指定页面！<br />
				      如果浏览器无法跳转，<a href="' . $url . '">请点击此处</a>。</p></td>
				    </tr>
				  </table>
				</div>
				</body>
				</html>';
		echo $msg;
		exit ();
	}
	
   /**
	* 警告信息，返回前页
	*/
    public function Get_admin_alert($show = '没有权限！禁止操作！') {
        $msg = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <script type="text/javascript">
                alert("'.$show.'");
                javascript:history.go(-1);
                </script>';
        echo $msg;
        exit ();
    }
	
	/**
	 * 警告信息，跳转指定地址
	 */
    public function Get_admin_insert($url,$show = '没有权限！禁止操作！') {
        $msg = '<meta http-equiv="content-type" content="text/html; charset=utf-8" />
                <script type="text/javascript">
                alert("'.$show.'");
                document.location.href="'.$url.'";
                </script>';
        echo $msg;
        exit ();
    }

	//========================
} //end class
?>