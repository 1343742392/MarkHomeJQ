<?php /*a:3:{s:73:"D:\MyFile\Code\Website\MarkHomeJQ\application\index\view\index\index.html";i:1739187639;s:77:"D:\MyFile\Code\Website\MarkHomeJQ\application\index\view\common\markbook.html";i:1739186952;s:73:"D:\MyFile\Code\Website\MarkHomeJQ\application\index\view\common\back.html";i:1739182847;}*/ ?>
<!DOCTYPE html>
<html lang="zh" style="height:100%">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='icon' href="favicon.ico">    
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <!-- <script src="static/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="static/css/app87.css">
    <script src="static/js/oldcode.js"></script>
    <script src="static/js/jquery.js"></script>
    <script src="static/js/md5.js"></script>
    <!-- <script type="module" src="static/js/request"></script> -->
    <script type="text/javascript" src="static/js/htmlparser.js"></script>

    <script type="module" src="static/js/main97big.js"></script>
    <script  type="text/javascript">
  
    </script>
    <title>书签主页</title>
</head>
<body>
    <i class="bi bi-0-circle"></i>
    <div id="app">
        <div class="main">
            <div class="modal" id="dialogDiv" tabindex="-1" style="display: none;background-color: #00000040;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="dailogTitleH">title</h5>
                        </div>
    
                        <div class="modal-body">
                            <p id="dialogMsgP"></p>
                        </div>
    
                        <div class="modal-footer">
                            <button type="button" id="dialogCloseBtn" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="loginPage" class="h-100 center-container "  style="display: none;flex-direction: column;">
                <div class="radius-card w-100 p-4">
                    <h1 class="font-weight-bold m-2">书签主页</h1>
                    <p class="font-bold text-muted small mb-4">摆脱设备浏览器限制 网页式书签</p>
                
                    <div class="input-group mb-3">
                        <input type="text" id="mailInput" class="form-control" placeholder="邮箱" >
                        
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" id="verifBtn" type="button">发送验证码</button>
                        </div>
                    </div>

                    <input type="text" id="verifCodeInput"  class="form-control mb-3" placeholder="邮箱接收到的验证码">

                    <button id="loginOrRegBtn" class="btn btn-primary btn-block">注册或者登录</button>
                </div>
		
		        <a style="margin-top:40px;     font-size: 12px;color:#9a9a9a" href="https://beian.miit.gov.cn/" target="_blank">湘ICP备18014381号</a>
                

            </div>

            <div id="homePage" class="home-page" >
                <div  id="homeHeadDiv" >
                    <div  class="home-head radius-card d-flex justify-content-between">
                        <svg id="userSvg"  class="click-pointer icon"  t="1643803198497" style="margin: 5px;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4479" width="40" height="40"><path d="M512 1022.1056c-282.3168 0-511.232-228.864-511.232-511.0784S229.632 0 512 0c282.3168 0 511.232 228.8128 511.232 511.0272 0 282.2656-228.864 511.0784-511.232 511.0784z m0-926.2592a415.2832 415.2832 0 0 0-415.3856 415.232c0 107.2128 41.0624 204.6464 107.8272 278.3232 60.16-29.1328 38.0416-4.9152 116.736-37.2736 80.5888-33.1264 99.6352-44.6464 99.6352-44.6464l0.768-76.288s-30.1568-22.8864-39.5264-94.72c-18.8928 5.4272-25.088-22.016-26.2144-39.424-1.024-16.896-10.9568-69.4784 12.0832-64.7168-4.7104-35.1744-8.0896-66.8672-6.4-83.6608 5.7344-58.88 62.976-120.5248 151.0912-124.9792 103.68 4.4544 144.7424 66.048 150.528 124.928 1.6384 16.8448-2.048 48.5376-6.7584 83.6096 23.04-4.6592 13.0048 47.872 11.8784 64.7168-1.024 17.408-7.3728 44.7488-26.2144 39.3728-9.4208 71.7824-39.5776 94.464-39.5776 94.464l0.7168 75.9296s19.0976 10.8032 99.6352 43.9296c78.6944 32.3584 56.576 9.5744 116.736 38.7584a413.184 413.184 0 0 0 107.776-278.3744A415.232 415.232 0 0 0 512 95.7952z" fill="#8a8a8a" p-id="4480"></path></svg>
    
                        <svg id="setSvg" class="click-pointer icon" t="1674907312248"  viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2701" width="48" height="48"><path d="M512 328c-100.8 0-184 83.2-184 184S411.2 696 512 696 696 612.8 696 512 612.8 328 512 328z m0 320c-75.2 0-136-60.8-136-136s60.8-136 136-136 136 60.8 136 136-60.8 136-136 136z" p-id="2702" fill="#8a8a8a" data-spm-anchor-id="a313x.7781069.0.i4" class="selected"></path><path d="M857.6 572.8c-20.8-12.8-33.6-35.2-33.6-60.8s12.8-46.4 33.6-60.8c14.4-9.6 20.8-27.2 16-44.8-8-27.2-19.2-52.8-32-76.8-8-14.4-25.6-24-43.2-19.2-24 4.8-48-1.6-65.6-19.2-17.6-17.6-24-41.6-19.2-65.6 3.2-16-4.8-33.6-19.2-43.2-24-14.4-51.2-24-76.8-32-16-4.8-35.2 1.6-44.8 16-12.8 20.8-35.2 33.6-60.8 33.6s-46.4-12.8-60.8-33.6c-9.6-14.4-27.2-20.8-44.8-16-27.2 8-52.8 19.2-76.8 32-14.4 8-24 25.6-19.2 43.2 4.8 24-1.6 49.6-19.2 65.6-17.6 17.6-41.6 24-65.6 19.2-16-3.2-33.6 4.8-43.2 19.2-14.4 24-24 51.2-32 76.8-4.8 16 1.6 35.2 16 44.8 20.8 12.8 33.6 35.2 33.6 60.8s-12.8 46.4-33.6 60.8c-14.4 9.6-20.8 27.2-16 44.8 8 27.2 19.2 52.8 32 76.8 8 14.4 25.6 22.4 43.2 19.2 24-4.8 49.6 1.6 65.6 19.2 17.6 17.6 24 41.6 19.2 65.6-3.2 16 4.8 33.6 19.2 43.2 24 14.4 51.2 24 76.8 32 16 4.8 35.2-1.6 44.8-16 12.8-20.8 35.2-33.6 60.8-33.6s46.4 12.8 60.8 33.6c8 11.2 20.8 17.6 33.6 17.6 3.2 0 8 0 11.2-1.6 27.2-8 52.8-19.2 76.8-32 14.4-8 24-25.6 19.2-43.2-4.8-24 1.6-49.6 19.2-65.6 17.6-17.6 41.6-24 65.6-19.2 16 3.2 33.6-4.8 43.2-19.2 14.4-24 24-51.2 32-76.8 4.8-17.6-1.6-35.2-16-44.8z m-56 92.8c-38.4-6.4-76.8 6.4-104 33.6-27.2 27.2-40 65.6-33.6 104-17.6 9.6-36.8 17.6-56 24-22.4-30.4-57.6-49.6-97.6-49.6-38.4 0-73.6 17.6-97.6 49.6-19.2-6.4-38.4-14.4-56-24 6.4-38.4-6.4-76.8-33.6-104-27.2-27.2-65.6-40-104-33.6-9.6-17.6-17.6-36.8-24-56 30.4-22.4 49.6-57.6 49.6-97.6 0-38.4-17.6-73.6-49.6-97.6 6.4-19.2 14.4-38.4 24-56 38.4 6.4 76.8-6.4 104-33.6 27.2-27.2 40-65.6 33.6-104 17.6-9.6 36.8-17.6 56-24 22.4 30.4 57.6 49.6 97.6 49.6 38.4 0 73.6-17.6 97.6-49.6 19.2 6.4 38.4 14.4 56 24-6.4 38.4 6.4 76.8 33.6 104 27.2 27.2 65.6 40 104 33.6 9.6 17.6 17.6 36.8 24 56-30.4 22.4-49.6 57.6-49.6 97.6 0 38.4 17.6 73.6 49.6 97.6-6.4 19.2-14.4 38.4-24 56z" p-id="2703" fill="#8a8a8a"></path></svg>
                    </div>
                </div>

                <div class="mark-book radius-card d-flex align-items-end">
    <div id="mb-left" class="mb-left hide-rollbar">
        <div id="foldersDiv" class='w-100 folders align-items-center' >
            
        </div>
    </div>

    <div  class="mb-right hide-rollbar" >
        <div id="contentDiv" class="files">

        </div>
    </div>
</div>

                <div id="editBtns" style="display: none !important;" class="radius-card mb-3 w-100 p-3 d-flex justify-content-around">
                    <button id="copyBtn" class="btn btn-secondary">
                        <svg class="pt-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-copy" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 5a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1h1v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h1v1z"/>
                        </svg>
                        复制
                    </button>

                    <button id="pasteBtn" class="btn btn-secondary"style="display: none">
                        <svg class="pt-1" t="1738501023581" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="19532" width="16" height="16"><path d="M84.626 1017.905a79.165 79.165 0 0 1-78.53-78.531V255.659a79.238 79.238 0 0 1 78.53-78.775h683.691a79.262 79.262 0 0 1 78.775 78.775v683.715a79.238 79.238 0 0 1-78.775 78.53H84.627z m-6.753-764.88v688.982l3.852 3.852 3.12 0.244h686.13l4.095-4.096 0.244-2.853V253.026l-4.096-4.096-3.145-0.244H81.97l-4.096 4.34z m825.612 594.067v-71.778h38.522l3.852-4.096 0.244-3.145V81.97l-4.096-3.852-2.853-0.244H253.026l-4.096 4.096-0.244 2.901v35.645h-71.802V84.626a79.238 79.238 0 0 1 78.799-78.53h683.69a79.214 79.214 0 0 1 78.532 78.53v683.691a79.19 79.19 0 0 1-78.531 78.75h-35.889z" p-id="19533" fill="#ffffff"></path></svg>
                        粘贴
                    </button>

                    <button  id='deleteBtn' class="btn btn-secondary">
                        <svg style="    padding-top: 0.1rem;" t="1738503733381" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="25886" width="16" height="16"><path d="M544 64C279.04 64 64 279.04 64 544S279.04 1024 544 1024 1024 808.96 1024 544 808.96 64 544 64zM544 960C314.24 960 128 773.76 128 544S314.24 128 544 128C773.76 128 960 314.24 960 544S773.76 960 544 960zM588.16 540.8l168.32-166.4c12.8-12.8 12.8-33.28 0-45.44-12.8-12.8-33.28-12.8-46.08 0L542.08 494.08 376.96 328.32c-12.8-12.8-33.28-12.8-45.44 0-12.8 12.8-12.8 33.28 0 45.44l164.48 165.76-174.72 172.8c-12.8 12.8-12.8 33.28 0 45.44 12.8 12.8 33.28 12.8 46.08 0l174.08-172.16 171.52 172.16c12.8 12.8 33.28 12.8 45.44 0 12.8-12.8 12.8-33.28 0-45.44L588.16 540.8z" p-id="25887" fill="#ffffff"></path></svg>
                        删除
                    </button>

                    <button   id='editObjectBtn'  class="btn btn-secondary">
                        <svg class="pt-1" t="1738501085111" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="20633" width="16" height="16"><path d="M631.808 100.864H187.904c-69.12 0-126.464 54.784-126.464 122.88v611.328c0 68.096 56.832 122.88 126.976 122.88h632.832c70.144 0 126.976-54.784 126.976-122.88V409.088h-63.488v425.984c0 34.304-28.672 61.44-64 61.44H188.416c-35.328 0-64-27.648-64-61.44V223.744c0-33.792 28.672-61.44 62.976-61.44h443.904v-61.44z" p-id="20634" fill="#ffffff"></path><path d="M459.264 507.392c-6.144 5.632-9.216 13.312-9.216 21.504s3.584 15.872 9.216 21.504c12.288 11.776 32.256 11.776 45.056 0L952.32 117.76c6.144-5.632 9.216-13.312 9.216-21.504s-3.584-15.872-9.216-21.504c-12.288-11.776-32.256-11.776-45.056 0L459.264 507.392z" p-id="20635" fill="#ffffff"></path></svg>
                        编辑
                    </button>

                    <button id="selectAll" class="btn btn-secondary">
                        <svg class="pt-1" t="1738501130453" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="21782" width="16" height="16"><path d="M0.000244 95.953148v831.593948c0 52.974134 42.979014 95.953148 95.953148 95.953148h831.593948c52.974134 0 95.953148-42.979014 95.953148-95.953148v-831.593948c0-52.974134-42.979014-95.953148-95.953148-95.953148h-831.593948c-52.974134 0-95.953148 42.979014-95.953148 95.953148z m895.562714 863.578331h-767.625183c-35.282772 0-63.968765-28.685993-63.968766-63.968765v-767.625183c0-35.282772 28.685993-63.968765 63.968766-63.968766h767.625183c35.282772 0 63.968765 28.685993 63.968765 63.968766v767.625183c0 35.282772-28.685993 63.968765-63.968765 63.968765z" p-id="21783" fill="#ffffff"></path><path d="M825.896974 352.128062L486.662616 691.362421c-24.987799 24.987799-65.468033 24.987799-90.455832 0L260.573011 555.628697c-12.493899-12.493899-12.493899-32.783992 0-45.177941 12.493899-12.493899 32.783992-12.493899 45.277892 0L430.290142 634.889995c6.196974 6.196974 16.391996 6.196974 22.58897 0l327.939873-327.939873c12.493899-12.493899 32.783992-12.493899 45.277892 0 12.293997 12.493899 12.293997 32.684041-0.199903 45.17794z" p-id="21784" fill="#ffffff"></path></svg>
                        全选
                    </button>
                </div>
                

                <div id="homeBackBtn" class="radius-card  p-3 mb-5" style="display: none;">
                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>

                <div id="searchDiv" class="home-bottom radius-card">
                    <div class="input-group">
                        <input id="searchWordInput" type="text" class="form-control" placeholder="搜索" aria-label="Recipient's username" aria-describedby="button-addon2">
                        
                        <div class="input-group-append">
                            <button class="search-btn btn btn-outline-secondary" type="button" id="searchBtn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="userPage" class="h-100 center-container" style="display: none">
                <div class="radius-card w-100 p-4">
                    <svg  t="1643803198497" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="4479" width="40" height="40"><path d="M512 1022.1056c-282.3168 0-511.232-228.864-511.232-511.0784S229.632 0 512 0c282.3168 0 511.232 228.8128 511.232 511.0272 0 282.2656-228.864 511.0784-511.232 511.0784z m0-926.2592a415.2832 415.2832 0 0 0-415.3856 415.232c0 107.2128 41.0624 204.6464 107.8272 278.3232 60.16-29.1328 38.0416-4.9152 116.736-37.2736 80.5888-33.1264 99.6352-44.6464 99.6352-44.6464l0.768-76.288s-30.1568-22.8864-39.5264-94.72c-18.8928 5.4272-25.088-22.016-26.2144-39.424-1.024-16.896-10.9568-69.4784 12.0832-64.7168-4.7104-35.1744-8.0896-66.8672-6.4-83.6608 5.7344-58.88 62.976-120.5248 151.0912-124.9792 103.68 4.4544 144.7424 66.048 150.528 124.928 1.6384 16.8448-2.048 48.5376-6.7584 83.6096 23.04-4.6592 13.0048 47.872 11.8784 64.7168-1.024 17.408-7.3728 44.7488-26.2144 39.3728-9.4208 71.7824-39.5776 94.464-39.5776 94.464l0.7168 75.9296s19.0976 10.8032 99.6352 43.9296c78.6944 32.3584 56.576 9.5744 116.736 38.7584a413.184 413.184 0 0 0 107.776-278.3744A415.232 415.232 0 0 0 512 95.7952z" fill="#2aa515" p-id="4480"></path></svg>
            
                    <p id="userMailP">mail</p>
    
                    <button id="loginOutBtn" class="btn w-100 btn-primary mb-3 p-2 center-container">
                        <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M7.5 1v7h1V1h-1z"/>
                            <path d="M3 8.812a4.999 4.999 0 0 1 2.578-4.375l-.485-.874A6 6 0 1 0 11 3.616l-.501.865A5 5 0 1 1 3 8.812z"/>
                        </svg>

                        退出登录
                    </button>
    
                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="setPage" class="h-100 center-container" style="display: none">
                <div class="radius-card w-100 p-3">
                    <button id="addBtn" class="btn w-100 btn-primary mb-3 p-2 center-container">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                        </svg>

                        添加书签
                    </button>

                    <button id="editBtn" class="btn w-100 btn-info mb-3 p-2 center-container">
                        <svg  class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>

                        管理书签
                    </button>

                    <button id="importBtn" class="btn w-100 btn-info mb-3 p-2 center-container" onclick="$('#favoritesInput').click()">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1h-2z"/>
                            <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        导入书签
                    </button>
                    <input id='favoritesInput' type="file" style='display:none'>


                    <button id="outputBtn" class="btn w-100 btn-primary mb-3 p-2 center-container">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                            <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>

                        导出书签
                    </button>

                    <button id="engineBtn" class="btn w-100 btn-info mb-3 p-2 center-container">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>

                        搜索引擎
                    </button>

                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="editFolderPage" class="h-100 center-container" style="display: none">
                <div class='radius-card p-3 w-100'>
                    <p style="color:#666;">修改文件夹名字</p>
                    <input type="text" class="form-control mb-2" id="folderName" placeholder=""/>
                    <button class="btn w-100 btn-primary mb-2 p-2 " value="">修改</button>
                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="editFilePage" class="h-100 center-container" style="display: none">
                <div class='radius-card p-3 w-100'>
                    <p style="color:#666;">修改书签</p>
                    <input type="text" class="form-control mb-2" id="fileNameInput" placeholder=""/>
                    <input type="text" class="form-control mb-2" id="fileUrlInput" placeholder=""/>
                    <button class="btn w-100 btn-primary mb-2 p-2 ">修改</button>
                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="addPage" class="h-100 center-container" style="display: none">
                <div class='radius-card p-3 w-100'>
                    <input type="text" class="form-control mb-2" id="addUrlInput" placeholder="网址 例如:https://www.baidu.com/">

                    <input type="text" class="form-control mb-2" id="addNameInput" placeholder="名字 例如:百度">
                
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="folderNameInput" placeholder="新文件夹名称 用>号分层">
                        
                        <div class="input-group-append">
                            <button id="dropdown" class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"
                            >
                                现有文件夹
                            </button>

                            <div class="dropdown-menu" id="folderSelectDiv">
                                <a class="dropdown-item" href="#">Action</a>

                            </div>
                        </div>
                    </div>

                    <button class="btn w-100 btn-primary mb-2 p-2 center-container" id="addFileBtn">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"  viewBox="0 0 16 16">
                            <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                        </svg>

                        添加
                    </button>

                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

            <div id="enginePage" class="h-100 center-container" style="display: none">
                <div class='radius-card p-4 w-100'>
                    <p style="color:#666;margin-top:0px">搜索引擎选择</p>
                
                    <div class="btn-group mb-5 w-100" >
                        <button id="baidu" type="button" class="btn btn-outline-secondary w-25">百度</button>
                        <button id="bing" type="button" class="btn btn-outline-secondary w-25">必应</button>
                        <button id="google" type="button" class="btn btn-outline-secondary w-25">谷歌</button>
                        <button id="kuake" type="button" class="btn btn-outline-secondary w-25">夸克</button>
                    </div>

                    <button class="btn w-100 btn-secondary p-2 center-container" onclick="window.back()">
    <svg  class="mr-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
    </svg>

    返回
</button>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
