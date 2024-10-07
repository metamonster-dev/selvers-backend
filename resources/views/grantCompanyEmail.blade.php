<h1 style="color: mediumpurple;font-size: 18px;">안녕하세요, {{$name}}님!</h1>
<div style="font-weight: bold;margin-bottom: 18px;color: #555555;">마이스 메이트에 호스트 회원 전환 신청이 승인 되었습니다. 가입해 주셔서 감사합니다. 이제 다양한 행사를 주최 할 수 있습니다.</div>

<label style="color: black"><b>계정 정보</b></label>
<table style="border-collapse: collapse;color: black;text-align: left;width: 400px;font-size: 12px;">
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">회사명</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$company_name}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">가입일시</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$time}}</td>
    </tr>
</table>

<div style="display:flex;margin: 20px 0;">
    <button style="border-radius: 6px;border: 0px solid mediumpurple;background: mediumpurple;padding: 11px 13px;font-size: 12px;"> <a style="color: white;text-decoration: none;" href="#">행사 등록 하러 가기</a></button>
</div>

<div style="font-weight: bold;margin-bottom: 18px;color: #555555;">{{$name}}님이 직접 전환하지 않았다면 <a href="#">문의하기</a>로 신고해주세요.<br>소중한 정보, 안전하게 지켜드리겠습니다.</div>