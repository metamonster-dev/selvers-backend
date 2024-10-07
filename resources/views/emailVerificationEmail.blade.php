<h1 style="color: mediumpurple;font-size: 18px;">{{$name}}님, 마이스 메이트에 가입해 주셔서 감사합니다.</h1>
<div style="font-weight: bold;margin-bottom: 18px;color: #555555;">회원이 되신 것을 환영합니다. <br>이제 다양한 행사에 참여하거나 원하는 행사를 주최할 수 있습니다.</div>

<label style="color: black"><b>계정 정보</b></label>
<table style="border-collapse: collapse;color: black;text-align: left;width: 400px;font-size: 12px;">
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">이름</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$name}}</td>
    </tr>
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">이메일주소</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$email}} 
            <button style="border: 0px solid mediumpurple;border-radius: 6px;background: mediumpurple;padding: 6px 4px;margin: 0 8px;font-size: 12px;">
                <a style="color: white;text-decoration: none;" href="{{ route('auth.verity', $token) }}">이메일 인증하기</a>
            </button>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid gray;border-left: none;padding: 4px;background: #d3d3d3;">가입일시</td>
        <td style="border: 1px solid gray;border-right: none;padding: 4px;">{{$time}}</td>
    </tr>
</table>

<div style="display:flex;margin: 20px 0;">
    <button style="border-radius: 6px;border: 0px solid mediumpurple;background: mediumpurple;padding: 11px 13px;font-size: 12px;color: white;"> <a href="#">다른 행사 둘러보기</a></button>
    <button style="border-radius: 6px;border: 1px solid mediumpurple;background: white;padding: 11px 13px;font-size: 12px;color: mediumpurple;font-weight: bold;margin-left: 8px;"> <a href="#">서비스 소개 보기</a></button>
</div>

<div style="font-weight: bold;margin-bottom: 18px;color: #555555;">{{$name}}님이 직접 가입하지 않았다면 <a href="#">문의하기</a>로 신고해주세요.<br>소중한 정보, 안전하게 지켜드리겠습니다.</div>