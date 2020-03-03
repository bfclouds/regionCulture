<?php
/**
 * User: coderd
 * Date: 2018/8/6
 * Time: 17:14
 */

namespace App\Lib\Support;


use App\Lib\ApiView;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PocFramework\Exception\Http\ApiException;

/**
 * Class Email
 * @package App\Lib\Support
 */
class Email
{

    const SEND_FROM = '834298507@qq.com';    //发送邮箱
    const SEND_FROM_USER_NAME = '地区文化系统';   //发送名称
    const SEND_SUBJECT = '地区文化系统验证码';       //发送标题
    const PASSWORD = 'pjjerqssvkngbaih';

    public function email($sendTo, $sendToUserName, $code) {
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //服务器配置
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->CharSet ="UTF-8";                     //设定邮件编码
            $mail->SMTPDebug = 0;                        // 调试模式输出
            $mail->isSMTP();                             // 使用SMTP
            $mail->Host = 'smtp.qq.com';                // SMTP服务器
            $mail->SMTPAuth = true;                      // 允许 SMTP 认证
            $mail->Username = self::SEND_FROM;                // SMTP 用户名  即邮箱的用户名
            $mail->Password = self::PASSWORD;             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
            $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持

            $mail->setFrom(self::SEND_FROM, self::SEND_FROM_USER_NAME);  //发件人
            $mail->addAddress($sendTo, $sendToUserName);  // 收件人
                //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
            $mail->addReplyTo(self::SEND_FROM, self::SEND_FROM_USER_NAME); //回复的时候回复给哪个邮箱 建议和发件人一致
                //$mail->addCC('cc@example.com');                    //抄送
                //$mail->addBCC('bcc@example.com');                    //密送

                //发送附件
                // $mail->addAttachment('../xy.zip');         // 添加附件
                // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名

                //Content
            $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = self::SEND_SUBJECT;
            $mail->Body    = '验证码3分钟内有效，请及时使用：'. $code ;
            $mail->AltBody = '验证码3分钟内有效，请及时使用：'. $code;
            //dd($mail);
            $mail->send();
            return true;
        } catch (Exception $e) {
            throw new ApiException([$e->getMessage()], ApiView::SERVER_FAILURE);
        }
    }
}