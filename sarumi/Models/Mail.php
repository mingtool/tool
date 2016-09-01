<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/5
 * Time: 下午2:53
 */


class Mail
{

    public $mailObject = null;

    /**
     * 单例, 所有的子类都必需定义此属性
     *
     * @var Mail
     */
    protected static $instance = null;
    /**
     * 单例模式
     *
     * @return  \Mail
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __construct()
    {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host     = 'smtp.qq.com';
        $mail->SMTPAuth = true;
        //设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        //设置ssl连接smtp服务器的远程服务器端口号 可选465或587
        $mail->Port = 465;
        $mail->Username = '2199872598@qq.com';
        $mail->Password = 'zqygsjlaamizecie';
        $mail->CharSet  = "UTF-8";
        $mail->From     = '2199872598@qq.com';
        $mail->FromName = '布';
        $mail->isHTML(true);

        $this->mailObject = $mail;

    }

    public function send($subject,$body,$mails)
    {
        $this->mailObject->Subject = $subject;
        $this->mailObject->clearAddresses();
        foreach($mails as $n => $m){
            $this->mailObject->addAddress($m, $n);
        }
        $this->mailObject->Body = $body;

        if (!$this->mailObject->send()) {
            echo '|--邮件发送失败, ';
            echo '原因: ', $this->mailObject->ErrorInfo, "\n";
        } else {
            echo '|--邮件发送成功', "\n";
        }
    }
}