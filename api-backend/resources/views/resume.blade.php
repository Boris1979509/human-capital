<!DOCTYPE html>
<html lang="ru">
<style>
    @font-face {
        font-family: 'Golos';
        src: url({{resource_path('fonts/Golos/bold/Golos_Text_Bold.eot')}});
        src: url({{resource_path('fonts/Golos/bold/Golos_Text_Bold.eot?#iefix')}}) format('embedded-opentype'),
        url({{resource_path('fonts/Golos/bold/Golos_Text_Bold.woff2')}}) format('woff2'),
        url({{resource_path('fonts/Golos/bold/Golos_Text_Bold.woff')}}) format('woff'),
        url({{resource_path('fonts/Golos/bold/Golos_Text_Bold.ttf')}}) format('truetype');
        font-weight: 700;
        font-style: normal;
    }

    @font-face {
        font-family: 'Golos';
        src: url({{resource_path('fonts/Golos/medium/Golos_Text_Medium.eot')}});
        src: url({{resource_path('fonts/Golos/medium/Golos_Text_Medium.eot?#iefix')}}) format('embedded-opentype'),
        url({{resource_path('fonts/Golos/medium/Golos_Text_Medium.woff2')}}) format('woff2'),
        url({{resource_path('fonts/Golos/medium/Golos_Text_Medium.woff')}}) format('woff'),
        url({{resource_path('fonts/Golos/medium/Golos_Text_Medium.ttf')}}) format('truetype');
        font-weight: 500;
        font-style: normal;
    }

    @font-face {
        font-family: 'Golos';
        src: url({{resource_path('fonts/Golos/regular/Golos_Text_Regular.eot')}});
        src: url({{resource_path('fonts/Golos/regular/Golos_Text_Regular.eot?#iefix')}}) format('embedded-opentype'),
        url({{resource_path('fonts/Golos/regular/Golos_Text_Regular.woff2')}}) format('woff2'),
        url({{resource_path('fonts/Golos/regular/Golos_Text_Regular.woff')}}) format('woff'),
        url({{resource_path('fonts/Golos/regular/Golos_Text_Regular.ttf')}}) format('truetype');
        font-weight: 400;
        font-style: normal;
    }

    #footer {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 48px;
    }

    .container {
        font-family: Golos;
    }

    .row {
        width: 1190px;
        margin-bottom: 32px;
        /*display: flex;*/
        /*flex-direction: row;*/
    }

    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    .left-column {
        width: 320px;
        float: left;
    }

    .right-column {
        width: 814px;
        float: left;
    }

    .avatar {
        width: 174px;
        height: 174px;
    }

    .avatar img {
        border-radius: 87px;

        width: 174px;
        height: 174px;
        object-fit: contain;
    }

    .header {
        display: flex;
        margin-bottom: 40px;
    }

    .name {
        font-style: normal;
        font-weight: bold;
        font-size: 40px;
        line-height: 44px;
        color: #04153E;
        margin-bottom: 12px;
    }

    .info {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        opacity: 0.48;
        margin-bottom: 24px;
    }

    .phone, .mail {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 16px;
        color: #04153E;
        margin-bottom: 12px;
        display: inline-block;
        height: 16px;
        margin-left: 8px;
    }

    .img {
        height: 16px;
        width: 16px;
        display: inline-block;
    }

    .img img {
        height: 16px;
        width: 16px;
        margin-top: 4px;
    }

    .experience-title {
        font-style: normal;
        font-weight: bold;
        font-size: 24px;
        line-height: 28px;
        color: #04153E;
        margin-bottom: 8px;
    }

    .experience-value {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        opacity: 0.48;
    }

    .work-item-dates {
        font-style: normal;
        font-weight: bold;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        margin-bottom: 8px;
    }

    .work-item-duration {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        opacity: 0.72;
    }

    .work-item-company {
        font-style: normal;
        font-weight: bold;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        margin-bottom: 8px;
    }

    .work-item-site {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        opacity: 0.48;
        margin-bottom: 8px;
    }

    .work-item-position {
        margin-bottom: 24px;
    }

    .work-item-position, .work-item-description {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        opacity: 0.72;
    }

    .work-experience {
        margin-bottom: 40px;
    }

    .education-title {
        margin-bottom: 24px;
        font-style: normal;
        font-weight: bold;
        font-size: 24px;
        line-height: 28px;
        color: #04153E;
    }

    .title {
        font-style: normal;
        font-weight: bold;
        font-size: 24px;
        line-height: 28px;
        color: #04153E;
        margin-bottom: 32px;
    }

    .text {
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: #04153E;
        opacity: 0.72;
        margin-bottom: 40px;
    }

    .footer-text {
        font-style: normal;
        font-weight: normal;
        font-size: 10px;
        line-height: 12px;
        color: #04153E;
        opacity: 0.32;
        margin-bottom: 8px;
    }
</style>
<body>
<div class="container">
    <div class="header">
        <div class="row">
            <div class="left-column">
                <div class="avatar">
                    <img src="{{$resume->getAvatarUrl()}}" alt="">
                </div>
            </div>
            <div class="right-column">
                <div class="name">{{$resume->getName()}}</div>
                <div class="info">{{$resume->getAge()}} лет, {{$resume->getCity()}}</div>
                <div>
                    <div class="img"><img src="{{resource_path('img/telephone_16.png')}}" alt=""/></div>
                    <div class="phone">{{$resume->getPhone()}}</div>
                </div>
                <div>
                    <div class="img"><img src="{{resource_path('img/email_16.png')}}" alt=""/></div>
                    <div class="mail">{{$resume->getMail()}}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="work-experience">
        <div class="row">
            <div class="left-column">
                <div class="experience-title">
                    Опыт работы
                </div>
                <div class="experience-value">
                    {{$resume->getTotalExperience()}}
                </div>
            </div>
        </div>
        @foreach($resume->getJobs() as $job)
            <div class="row">
                <div class="left-column">
                    <div class="work-item-dates">{{$job->getPeriod()}}</div>
                    <div class="work-item-duration">{{$job->getPeriodDuration()}}</div>
                </div>
                <div class="right-column">
                    <div class="work-item-company">{{$job->getCompany()}}</div>
                    <div class="work-item-site">{{$job->getCompanyWebsite()}}</div>
                    <div class="work-item-position">{{$job->getPosition()}}</div>
                    <div class="work-item-description">{{$job->getDescription()}}</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="education">
        <div class="education-title">Образование</div>
        @foreach($resume->getEducation() as $education)
            <div class="row">
                <div class="left-column">
                    <div class="work-item-dates">{{$education->getQuality()}}</div>
                    <div class="work-item-duration">{{$education->getYear()}}</div>
                </div>
                <div class="right-column">
                    <div class="work-item-company">{{$education->getUniversity()}}</div>
                    <div class="work-item-position">{{$education->getSpecialty()}}</div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="title">
        Ключевые навыки
    </div>
    <div class="text">{{$resume->getSkills()}}</div>
    <div class="title">
        Дополнительная информация
    </div>
    <div class="text">{{$resume->getDescription()}}</div>
    <div id="footer">
        <div class="footer-text">Подготовлено с помощью</div>
        <img src="{{resource_path('img/resume-logo.png')}}" alt="">
    </div>
</div>
</body>
</html>
