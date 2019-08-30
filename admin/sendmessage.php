<?php


//获取发送数据数组
function getDataArray($openid,$type,$name,$tel,$state,$content,$applytime,$sendtime,$cfg_regfailed,$page,$form_id)
{
    $data = array(
        'touser' => $openid,                //要发送给用户的openid
        'template_id' => $cfg_regfailed,    //改成自己的模板id，在微信后台模板消息里查看
        'page' => $page,                     //点击模板消息详情之后跳转连接
		'form_id' => $form_id,              //form_id
        'data' => array(
            'keyword1' => array(
                'value' => $type,            //账户类型
                'color' => "#3d3d3d"
            ),
            'keyword2' => array(
                'value' => $name,             //企业名称
                'color' => "#3d3d3d"
            ),
            'keyword3' => array(
                'value' => $tel,              //手机号码
                'color' => "#3d3d3d"
            ),
            'keyword4' => array(
                'value' => $state,            //审核状态
                'color' => "#3d3d3d"
            ),
			'keyword5' => array(
                'value' => $content,           //失败原因
                'color' => "#173177"
            ),
			'keyword6' => array(
                'value' => $applytime,         //申请时间
                'color' => "#3d3d3d"
            ),
			'keyword7' => array(
                'value' => $sendtime,          //审核时间
                'color' => "#3d3d3d"
            )

        ),
    );
    return $data;
}



?>
