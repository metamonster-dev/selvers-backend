<h1 style="color: mediumpurple;font-size: 18px;">안녕하세요, {{$name}}님! {{$event_name}}의 등록 승인이 완료되었습니다.</h1>
<div style="font-weight: bold;margin-bottom: 18px;color: #555555;">마이스 메이트에서 {{$event_name}}의 성공적 개최를 응원합니다.</div>

<label style="color: black"><b>행사 정보</b></label>
<table style="border-collapse: collapse;color: black;text-align: left;width: 400px;font-size: 12px;">
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">행사명</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$event_name}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">모집기간</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$recurit_start_date}} ~ {{$recurit_end_date}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">행사일시</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$event_start_date}} ~ {{$event_end_date}}</td>
    </tr>
</table>

<div style="display:flex;margin: 20px 0;">
    <button style="border-radius: 6px;border: 0px solid mediumpurple;background: mediumpurple;padding: 11px 13px;font-size: 12px;"> <a style="color: white;text-decoration: none;" href="#">마이스 메이트 바로가기</a></button>
</div>

<div style="font-weight: bold;margin-bottom: 18px;color: #555555;">{{$name}}님이 직접 행사를 등록하시지 않았다면 <a href="#">문의하기</a>로 신고해주세요.<br>소중한 정보, 안전하게 지켜드리겠습니다.</div>