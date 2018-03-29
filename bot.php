<?php
$access_token='XK1Z6+XOO43h/LK5nIBkg63J7CrD4KupFFE64jlr9MubxnIhzBUoZWV4C1GQabfKOR00JPbAwXbxm3prq5WB53xBD2wk+GgBtblpHnz/o498xJFe9m+LKpipR4ezZ+U7kPHHfZStdK54yIj12BjK8wdB04t89/1O/w1cDnyilFU=';	//ใส่ access token ของเรา ****
$content = file_get_contents('php://input');	//รับค่าจากล่องข้อความใน line
$events = json_decode($content, true);			//เปลี่ยน json ที่ line ส่งมา ให้เป็น array
if (!is_null($events['events'])) {				//ตรวจสอบว่ามีข้อมูลส่งมาหรือไม่
	foreach ($events['events'] as $event) {	
		$replyToken=$event['replyToken'];		//Token สำหรับส่งข้อความกลับ
		$text=$event['message']['text'];		//รับค่าข้อความที่ส่งเข้ามาในตัวแปร text
		
		
		/*ตั้งคำ ถาม / คำตอบให้กับบอท*/
		if($text=='สวัสดี'){
		$replytext='สวัสดีค่ะ';
		}
		
		if($text=='ต่าย'){
		$replytext='ขี้เหล่เหมือนผีกระหัง คอยจะกินแต่เลือด';
		}
		
		if($text=='บอลลี่'){
		$replytext='บอลลี่คนสวย อวบน่ารัก';
		}

		/*BOT เชิญคนเข้ากลุ่ม + เช็คข้อมูล line*/
		$AntiBot=$event['source']['userId'];
		if($AntiBot == ''){
		$replytext='ข้อมูลสมาชิกนี้ไม่ได้รับอนุญาติ';
		}
		
		/*เช็ค Userid Line*/
		if($text=='/iduser')
		{
		$textuserid=$event['source']['userId'];
		$replytext='LineID : '.$textuserid;
		}
		
		/*เช็ค roomId Line*/
		if($text=='/idroom')
		{
		$textroomid=$event['source']['groupId'];
		$replytext='groupId : '.$textroomid;
		}
		
		//สร้างข้อความตอบกลับ
		$messages = [
			[
			'type' => 'text',
			'text' => $replytext 
			]
		];
		$url = 'https://api.line.me/v2/bot/message/reply';	//url สำหรับตอบกลับ
		//$url = 'https://api.line.me/v2/bot/profile/'.urlencode($textusernew);	//url Profile
		$data = [
			'replyToken' => $replyToken,		//replyToken ใส่ตรงนี้
			'messages' => $messages,
		];
		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
														//headers สำหรับตอบกลับ
		$ch = curl_init($url);				//เริ่ม curl 
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");//ปรับเป็นแบบ post
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);	//ใส่ข้อความที่จะส่ง
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);	//ส่ง header
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);	
		curl_exec($ch);					//ส่งไปให้ไลน์ตอบกลับ
	}
}
echo 'OK Reply';
?>
