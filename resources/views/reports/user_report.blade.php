<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            size: A4;
            margin: 15mm 15mm 32mm 15mm;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            -webkit-font-smoothing: antialiased;
        }

        * {
            box-sizing: border-box;
        }

        /* Professional page layout */
        .page {
            width: 100%;
            height: 297mm;
            display: block;
            position: relative;
            font-family: 'Arial', 'Helvetica', sans-serif;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
            page-break-after: always;
            overflow: hidden;
            box-sizing: border-box;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .page-content {
            position: relative;
            z-index: 2;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            margin: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .content-wrapper,
        .content-wrapper-2 {
            position: relative;
            height: calc(100% - 70mm);
            padding-top: 10mm;
            padding-bottom: 30mm;
        }

        .content-wrapper-2 {
            margin-bottom: 0;
            max-height: calc(100% - 90mm);
            overflow: hidden;
        }

        .header,
        .header-2 {
            position: absolute;
            top: 8%;
            right: 20px;
            color: white;
            font-size: 15px;
            text-align: right;
        }

        .header-2 {
            top: 7%;
        }

        .header.header-4 {
            color: #000000;
            top: 9%;
        }

        .market-report h3 {
            padding: 0px;
            margin: 0;
        }

        .content,
        .content-2 {
            position: absolute;
            top: 12%;
            left: 32%;
            padding: 10mm;
            color: #fff;
            border-radius: 15px;
            text-align: left;
            font-size: 28px;
            line-height: 32px;
            font-weight: 300;
            width: 60%;
        }

        .content-2 {
            left: 5%;
            top: 12%;
            /* Moved higher to give more space */
            width: 85%;
            max-height: calc(100% - 100mm);
            /* Better height calculation for PDF */
            overflow: visible;
            /* Allow content to flow naturally */
        }

        .content-4 {
            left: 0px;
            width: 100%;
        }

        .content-5 {
            padding: 0;
            left: 12%;
            width: 80%;
        }

        .date {
            margin-top: 25px !important;
            font-size: 22px;
            margin-left: 32% !important;
        }

        .footer,
        .footer-2,
        .footer-4 {
            position: fixed;
            bottom: 22mm;
            left: 13%;
            right: 20mm;
            font-size: 10px;
            display: table;
            width: 80%;
            z-index: 999;
        }

        .footer p {
            display: table-cell;
            width: 33.33%;
            vertical-align: middle;
            margin: 0;
            padding: 0 10px;
        }



        .footer-2 {
            bottom: 22mm;
        }

        .footer-4 {
            bottom: 22mm;
        }

        .footer-2 p {
            width: 40% !important;
        }

        .footer-2 p.footer-address {
            width: 60% !important;
        }


        .footer p a {
            padding-left: 17%;
        }

        a {
            text-decoration: none;
            color: black;
        }

        h4.content-head {
            padding-top: 14px;
            text-transform: uppercase;
            font-size: 32px;
            font-weight: bold;
            color: #ff2969;
            padding-left: 13px;
            margin: 0;
        }

        .content.content-2 ol li {
            font-size: 16px;
            line-height: 48px;
            font-weight: bold;
        }

        .content p {
            margin: 0;
            padding: 8px;
        }

        .page3 .header-2 span {
            color: #000000;
        }

        .content-grap {
            margin-top: 30px;
            width: 100%;
            display: table;
        }

        .content-grap-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .content-grap-right {
            width: 100%;
            vertical-align: top;
        }

        /* .content-grap-right ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .content-grap-right ul li {
      margin: 8px;
      float: left;
      display: table-cell;
    }

    .content-grap-right ul li img {
      width: 100%;
      height: auto;
      max-width: 300px;
      background-color: #fff;
    }


    .content-grap-right img {
      width: 80%;
      height: auto;
      max-width: 300px;
    } */

        .content-grap-left table {
            width: 100%;
            border-collapse: collapse;
        }

        .content-grap-left tr td {
            border: 1px solid #000000;
            padding: 6px;
            font-size: 12px;
        }

        .score-grap {
            padding: 50px 0;
            text-align: center;
        }

        .score-grap img {
            width: 100%;
            height: auto;
            max-width: 600px;
        }

        .para-1 {
            margin-bottom: 15px;
        }

        .para-1 h4 {
            margin: 0 0 10px 7px;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            padding-top: 20px;
        }

        .para-1 p {
            margin: 0;
            padding: 0 7px;
            font-size: 14px;
            line-height: 1.8;
            text-align: justify;
            font-weight: 400;
        }

        .para-2 h4 {
            padding-top: 29px;
        }

        .para-2 {
            margin-bottom: 0px;
        }

        .para-3 h4 {
            padding-top: 15px;
        }

        .para-4 h4 {
            padding-top: 14px;
        }

        .para-5 h4 {
            padding-top: 30px;
        }

        .para-6 h4 {
            padding-top: 2px;
        }

        .two-column {
            margin-top: 14px;
            font-size: 15px;
            font-weight: 400;
            display: table;
            width: 100%;
        }

        .two-column .content-grap-left,
        .two-column .content-grap-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 10px;
        }

        .content-grap-right p span,
        .content-grap-left p span {
            color: #ff2969;
            font-weight: 700;
        }

        .grap-imag {
            margin: 10px 0;
        }

        .grap-imag img {
            border: 1px solid #ddd;
            width: 100%;
            height: auto;
            min-height: 150px;
        }

        /* Background images - Using absolute file paths for PDF generation */
        .page-1-bg {
            background-image: url("{{ public_path('Image/page1_optimized.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #2c3e50;
            /* Fallback color */
        }

        .page-2-bg {
            background-image: url("{{ public_path('Image/page2_optimized.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #34495e;
        }

        .page-3-bg {
            background-image: url("{{ public_path('Image/page6_optimized.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #ecf0f1;
            /* Fallback color */
        }

        .page-4-bg {
            background-image: url("{{ public_path('Image/page6_optimized.jpg') }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #ecf0f1;
            /* Fallback color */
        }

        /* Alternative approach using base64 encoded images for maximum compatibility */
        @if (file_exists(public_path('Image/page1_optimized.jpg'))) .page-1-bg-base64 {
            background-image: url("data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('Image/page1_optimized.jpg'))) }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #2c3e50;
        }

        @endif @if (file_exists(public_path('Image/page2_optimized.jpg'))) .page-2-bg-base64 {
            background-image: url("data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('Image/page2_optimized.jpg'))) }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #34495e;
        }

        @endif @if (file_exists(public_path('Image/page6_optimized.jpg'))) .page-3-bg-base64,
        .page-4-bg-base64 {
            background-image: url("data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('Image/page6_optimized.jpg'))) }}");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #ecf0f1;
        }

        @endif .chart-container {
            width: 100%;
            margin: 12px 0;
            /* Further reduced margin */
            padding: 6px;
            /* Further reduced padding */
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            max-height: none;
            /* Remove height restriction */
            overflow: visible;
        }

        .chart-row {
            width: 100%;
            margin-bottom: 20px;
            display: table;
            table-layout: fixed;
            border-spacing: 8px;
        }

        .chart-item {
            display: table-cell;
            width: 50%;
            padding: 8px;
            /* Reduced padding */
            text-align: center;
            vertical-align: top;
            /* Changed from middle to top */
            background: #fafafa;
            border-radius: 6px;
            border: 1px solid #e0e0e0;
            page-break-inside: avoid;
        }

        .chart-item img {
            /* Use natural image dimensions without forcing width/height */
            max-width: 280px;
            /* Fixed max width instead of percentage */
            max-height: 160px;
            /* Fixed max height for consistency */
            width: auto;
            height: auto;
            object-fit: scale-down;
            /* Scale down if needed but preserve ratio */
            object-position: center;
            border: 2px solid #f0f0f0;
            background-color: #fff;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .chart-item h4 {
            font-size: 14px;
            font-weight: 600;
            color: #ff2969;
            margin: 10px 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 200px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 4px;
            color: #6c757d;
            font-style: italic;
            font-size: 14px;
        }

        .chart-item p {
            font-size: 12px;
            margin: 5px 0;
            color: #333;
            text-align: center;
        }

        /* Fallback for odd number of charts */
        .chart-item:nth-child(odd):last-child {
            width: 60%;
            margin: 1%;
        }

        /* Optional: Add hover effects */
        .chart-item:hover {
            opacity: 0.9;
        }

        .chart-item img:hover {
            border-color: #ff2969;
        }

        /* Responsive adjustments for smaller screens */
        @media screen and (max-width: 600px) {
            .chart-item {
                width: 98%;
                margin: 1%;
            }
        }

        /* Hide footer icons in background images - made transparent for PDF generation */
        .icon-overlay,
        .icon,
        i[class*="fa-"],
        i[class*="glyphicon"],
        .glyphicon,
        [class*="icon-"],
        .material-icons,
        .footer *::before,
        .footer *::after,
        .footer p::before,
        .footer p::after,
        .footer a::before,
        .footer a::after {
            display: none !important;
            visibility: hidden !important;
            content: none !important;
            background-image: none !important;
        }

        /* Remove any background images that might contain icons */
        .footer,
        .footer *,
        .footer p,
        .footer a {
            background-image: none !important;
            background: none !important;
        }

        /* Ensure footer content appears properly */
        .footer {
            z-index: 999;
            background: transparent !important;
        }

        /* PDF-specific optimizations to prevent chart overflow */
        .chart-row {
            page-break-inside: avoid;
            margin-bottom: 12px;
            /* Further reduced for better spacing */
        }

        /* Ensure full-width charts don't overflow and maintain aspect ratio */
        .chart-item[style*="width: 100%"] {
            padding: 8px;
            /* Reduced padding for full-width items */
        }

        .chart-item[style*="width: 100%"] img {
            max-height: 120px;
            /* Smaller for full-width charts */
            max-width: 450px;
            /* Fixed width instead of percentage */
            width: auto;
            height: auto;
            object-fit: scale-down;
        }

        /* Ensure text content is visible and not cut */
        .content-2 p {
            page-break-inside: avoid;
            margin-bottom: 8px;
            /* Reduced margin */
        }

        /* Better spacing for chart pages */
        .page3 .content-2 {
            font-size: 13px;
            line-height: 18px;
        }

        /* Prevent any chart stretching by enforcing aspect ratio */
        .chart-item {
            min-height: auto;
            max-height: none;
        }

        .chart-item img[style*="width"] {
            width: auto !important;
            /* Override any inline width styles */
        }

        .content-head-sm {
            padding-top: 14px;
            font-size: 24px;
            font-weight: bold;
            color: #ff2969;
            margin: 0;
        }

        h4.content-head-sm {
            padding-top: 14px;
            font-size: 24px;
            font-weight: bold;
            color: #ff2969;
            margin: 0;
        }

        .city-overview-title {
            padding-top: 14px;
            font-size: 24px;
            font-weight: bold;
            color: #000000;
        }

        h5.content-head-sm {
            padding-top: 25px;
            font-size: 28px;
            font-weight: normal;
            color: #000;
            margin: 0;
            text-transform: uppercase;
        }

        .content-head-min {
            padding-top: 14px;
            font-size: 16px;
            font-weight: bold;
            color: #ff2969;
            margin: 0;
        }

        .content-head-round {
            padding-top: 14px;
            font-size: 16px;
            font-weight: bold;
            color: #ff2969;
            margin: 0;
        }

        .content-head-black {
            padding-top: 14px;
            font-size: 16px;
            font-weight: bold;
            color: #000;
            margin: 0;
            margin-top: 10px;
        }

        .chart-col-4 {
            display: table-cell;
            width: 40%;
            padding: 8px;
            /* Reduced padding */
            text-align: center;
            vertical-align: top;
            /* Changed from middle to top */
            background: #fafafa;
            border-radius: 6px;
            border: 1px solid #ff2969;
            page-break-inside: avoid;
        }

        .chart-col-8 {
            display: table-cell;
            width: 60%;
            padding: 8px;
            /* Reduced padding */
            text-align: left;
            vertical-align: top;
            /* Changed from middle to top */
            page-break-inside: avoid;
        }



        .chart-col-4 img {
            /* Use natural image dimensions without forcing width/height */
            max-width: 260px;
            /* Fixed max width instead of percentage */
            max-height: 110px;
            /* Fixed max height for consistency */
            width: auto;
            height: auto;
            object-fit: scale-down;
            /* Scale down if needed but preserve ratio */
            object-position: center;
            border: 2px solid #f0f0f0;
            background-color: #fff;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .chart-col-8 h4 {
            font-size: 14px;
            font-weight: 600;
            color: #ff2969;
            margin: 10px 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .phone_block {
            border: 1px solid #000;
            padding: 60px 25px 80px;
            border-radius: 20px;
        }

        .phone_block .content-head-sm {
            font-size: 24px;
            line-height: 28px;
        }

        .phone_no {
            background-image: url("{{ public_path('Image/arrow-icon.png') }}");
            background-size: 24px;
            ;
            background-repeat: no-repeat;
            background-position: left;
            padding-left: 30px;
            color: #000;
            font-size: 20px;
        }

        .phone_no {
            background: url("{{ public_path('Image/arrow-icon.png') }}") no-repeat;
            background-size: 18px;
            ;
            background-repeat: no-repeat;
            background-position: left;
            padding-left: 26px;
            color: #000;
            font-size: 25x;
            font-weight: bold;
        }

        .website_link {
            background: url("{{ public_path('Image/web-icon.png') }}") no-repeat;
            background-size: 18px;
            ;
            background-repeat: no-repeat;
            background-position: left;
            padding-left: 26px;
            color: #000;
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 4px;
        }
    </style>
</head>

<body>
    <!-- Page 1 -->
    <div class="page page-1-bg page-1-bg-base64">
        <div class="content-wrapper">
            <div class="header">
                <div class="market-report">
                    <h3>#Market Report</h3>
                    <span>
                        @if (isset($suburb) && !empty($suburb))
                        {{ $suburb }}
                        @endif

                        {{ $year }}
                    </span>
                </div>
            </div>
            <div class="content" style="top: 18%;">
                <p>{{ $suburb }} Property<br>Investment Outlook<br>{{ $year }}: Trends, Insights<br>and
                    Growth Potential</p>
                <p class="date">{{ $date }}</p>
            </div>
        </div>
        <div class="footer">
            <p>215/33 Lexington Dr,<br>Bella Vista NSW 2153</p>
            <p>0409 016 393<br>info@propwealth.com.au</p>
            <p><a href="https://propwealth.com.au/">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 2 -->
    <div class="page page-2-bg page-2-bg-base64">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2">
                <h4 class="content-head">Table of contents</h4>
                <ol>
                    <li>Introduction - PropWealth Next</li>
                    <li>City - Introduction</li>
                    <li>Scores</li>
                    <li>Demand-Supply - Houses (trend of price, inventory)</li>
                    <li>Demand-Supply - Units (trend of price, inventory)</li>
                    <li>Rental Analysis</li>
                    <li>Social Index</li>
                    <li>Glossary</li>
                </ol>
            </div>
        </div>
        <div class="footer footer-2">
            <p style="padding-left:30px;color: #000000;" class="footer-address">{{ $suburb }} Property Investment
                Outlook 2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 3 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>

            <div class="content content-2" style="color: #000000; font-size: 14px !important; line-height: 21px;">
                <h4 class="content-head-sm">PropWealth Next</h4>
                <p
                    style="color: #000000; font-size: 14px; line-height: 18px;font-weight:400;padding-left: 0;padding-right: 0;padding-bottom:0;">
                    <strong>Invest today in the next GROWTH HOTSPOTS</strong>
                </p>
                <p
                    style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;padding-left: 0;padding-right: 0;padding-top:0;">
                    PropWealth Next is designed to give investors more than just market data; it’s a clear,
                    data -backed perspective on where opportunity is heading next.
                    Through advanced suburb analytics, we identify key growth pockets, rental performance shifts, and
                    future trends shaping property value across Australia.
                    Every report is built to guide smarter decisions, combining verified data with local
                    market intelligence to help you act with confidence, not guesswork.
                    Your journey with PropWealth Next is about foresight, knowing not just where the
                    market stands, but where it’s moving next</p>
                <img width="480px" src="/Image/pro_welth_house.png" alt="PropWealth Next Invest" />
            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 4 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px;">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <h4 class="content-head-sm" style="display: inline;">{{ $suburb }}
                        </h4>
                        <h4 class="city-overview-title" style="display: inline; font-size: 24px; font-weight: bold;">
                            City Overview</h4>
                        @if(isset($charts['desc_1']) && !empty($charts['desc_1']))
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;padding:0; margin-top: 10px;;">
                            {{ $charts['desc_1'] }}
                        </p>
                        @else
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;padding:0; margin-top: 10px;;">
                            {{ $suburb }} is a major regional city that is located in the
                            {{ $suburbData['Sub Region (SA3)'] ?? ($suburbData['Region (SA4)'] ?? 'various') }},
                            Australia. It is part of the twin city of {{ $suburbData['Suburb'] }} and is located on the
                            Hume Highway and the northern side of the Murray River. {{ $suburb }} is the seat of
                            local govern - ment for the council area which also bears the city's name – the City of
                            {{ $suburb }}. It is on the
                            {{ $suburbData['Sub Region (SA3)'] ?? ($suburbData['Region (SA4)'] ?? 'various') }} border
                        </p>
                        @endif
                        @if (isset($charts['desc_2']) && !empty($charts['desc_2']))
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;padding:0; margin-top: 10px;;">
                            {{ $charts['desc_2'] }}
                        </p>
                        @else
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;padding:0;">
                            {{ $suburb }} has an urban population of 53,677[4] and is separated from its twin
                            city in Victoria, Wodonga, by the Murray River. Together, the two cities form an urban area
                            with a population of 97,793 in 2021.[5] It is 554 kilometres (344 mi) from the state capital
                            Sydney and 326 kilometres (203 mi) from the Victorian capital Melbourne.
                        </p>
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;padding:0;">Said to
                            be named after a village in England, United Kingdom, {{ $suburb }} developed as a
                            major transport link between New South Wales and Victoria and was proclaimed a city in 1946
                        </p>

                        @endif
                        <div class="chart-row" style="border-spacing:0">
                            <div class="chart-item" style="padding:0;border:0;background: transparent;">
                                <table border="1" cellspacing="0" cellpadding="0"
                                    style="border-collapse: collapse; margin-bottom: 10px;color: #000000; font-size: 10px;font-weight:400; line-height: auto; ">
                                    <tr>
                                        <th colspan="2" style="text-align: left;font-weight:400;padding: 0 5px;">
                                            Median house price</th>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">Median house price</td>
                                        <td style="padding: 0 5px;">$575,000</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">Avg rental yield</td>
                                        <td style="padding: 0 5px;">5.6%</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">Rental turnover</td>
                                        <td style="padding: 0 5px;">10</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">12 month rental growth</td>
                                        <td style="padding: 0 5px;">12%</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">Overall score</td>
                                        <td style="padding: 0 5px;">8 / 10</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="chart-item" style="padding:0;border:0;background: transparent;">
                                <table border="1" cellspacing="0" cellpadding="0"
                                    style="border-collapse: collapse; margin-bottom: 10px;color: #000000; font-size: 10px;font-weight:400;line-height: auto;">
                                    <tr>
                                        <th colspan="2" style="text-align: left;font-weight:400;padding: 0 5px;">
                                            House prices area growth</th>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">12 month</td>
                                        <td style="padding: 0 5px;">7.8%</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">3 yrs</td>
                                        <td style="padding: 0 5px;">5.6%</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">5 yrs</td>
                                        <td style="padding: 0 5px;">2%</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 0 5px;">10 yrs</td>
                                        <td style="padding: 0 5px;">12%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="chart-row" style="border-spacing:0">
                            <div class="chart-item"
                                style="padding:0;border:0;background: transparent;padding-right:5px;">
                                <div style="border:1px solid #ff2969; border-radius: 6px;padding:5px;">
                                    <img src="{{ public_path('Image/population_image.png') }}" style="width:80%"
                                        alt="" />
                                </div>
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400;">Current
                                    population of the city – 48,678 Population trend (SA4 – 2016, 2021) </p>
                            </div>
                            <div class="chart-item"
                                style="padding:0;border:0;background: transparent;padding-right:5px;">
                                <div style="border:1px solid #ff2969; border-radius: 6px;padding:5px;">
                                    <img src="{{ public_path('Image/Employment_image.png') }}" style="width:80%"
                                        alt="img" />
                                </div>
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; ">
                                    Employment – (SALM) – Small area labour market (top 5 categories)</p>
                            </div>
                        </div>
                        @if (isset($note) && !empty($note))
                        <p>{{ $note }}</p>
                        @endif

                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 5 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <h4 class="content-head-sm" style="padding-top:0 !important;margin:0 0 25px;">OVERALL SCORE
                            <span style="color: #000000;">– 8 / 10</span>
                        </h4>
                        <h5 class="content-head" style="text-align:center;margin:0 0 15px;font-size: 20px;">KEY SCORES
                        </h5>
                        <h6 style="text-align:center;color:#ff2969; font-weight:bold;font-size:17px;margin:0 0 15px;">
                            ______scores</h6>

                        <div class="text-align:center;margin:0 0 15px;">
                            <img style="display: block; margin:auto;" width="400px" src="/Image/graph2.png"
                                alt="PropWealth Next Invest" />
                        </div>
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; ">The Overall
                            Suburb Score is a composite index that combines multiple key metrics into a single score,
                            summarising the suburb’s relative desirability, investment potential, and liveability. </p>

                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 6 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px;">
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-black" style="display: inline; font-weight:500;margin-right: 6px;">1.
                        </div>
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 26px;">Affluency Score – </div>
                        <div class="content-head-black" style="display: inline;font-weight:400;">3/10 (socio
                            economics score)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The affluency score provides an assessment of a suburb's overall socio-economic standing. It
                            is informed by indicators such as the proportion of fully owned dwellings, income levels,
                            and investment yield. This score reflects long-term financial stability, housing security,
                            and wealth concentration within the community. A higher score indicates a more affluent and
                            economically resilient suburb, typically characterised by higher ownership rates and lower
                            transience.
                        </p>
                    </div>

                    <div class="metric-section">
                        <div class="content-head-black" style="display: inline; font-weight:500;margin-right: 6px;">2.
                        </div>
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Affordability Score –
                        </div>
                        <div class="content-head-black" style="display: inline; font-weight:400;">6/10 (buy
                            affordability score & rent affordability score)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The affordability score measures the financial accessibility of housing in a suburb by
                            combining two distinct perspectives: buy affordability and rent affordability. The buy
                            affordability component assesses how attainable home ownership is based on the relationship
                            between median property prices and household income. The rent affordability component
                            evaluates the proportion of income required to cover rental expenses. Higher scores in both
                            components indicate that the area offers accessible housing options for both purchasers and
                            tenants.
                        </p>
                    </div>

                    <div class="metric-section">
                        <div class="content-head-black" style="display: inline; font-weight:500;margin-right: 6px;">3.
                        </div>
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Liquidity Score – </div>
                        <div class="content-head-black" style="display: inline; font-weight:400;">5/10 (sales
                            turnover)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The liquidity score reflects the level of transactional activity within a suburb, as
                            measured by the frequency of property sales over a given period. It provides insight into
                            market dynamism and demand intensity. A higher score denotes a more active market, where
                            properties are being bought and sold at a higher rate, suggesting strong buyer engagement
                            and ease of entry or exit for investors and homeowners alike.
                        </p>
                    </div>

                    <div class="metric-section">
                        <div class="content-head-black" style="display: inline; font-weight:500;margin-right: 6px;">4.
                        </div>
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Supply Score – </div>
                        <div class="content-head-black" style="display: inline; font-weight:400;">7/10 (inventory
                            Score)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The supply score is based on inventory levels, which estimate how many months it would take
                            to clear the current stock of listed properties at the prevailing rate of sales. This metric
                            provides a forward-looking view of market balance. A lower inventory typically points to
                            tight supply and stronger demand pressure, while a higher inventory may signal slower
                            turnover or over-supply. For scoring purposes, a higher supply score indicates lower
                            inventory and therefore stronger market competitiveness.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property
                Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 7 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px;">
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-black" style="display: inline; font-weight:500;margin-right: 6px;">5.
                        </div>
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">PropWealth Investor Score
                            –</div>
                        <div class="content-head-black" style="display: inline; font-weight:400;">4/10 (Investor
                            Score)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The investor score is a proprietary metric developed by PropWealth to evaluate the
                            investment potential of a suburb. It draws on a range of underlying indicators such as
                            rental yield, price momentum, ownership composition, supply-demand dynamics,
                            and tenure stability. This score is designed to reflect the long-term attractiveness of a
                            suburb from an investment perspective. A higher score suggests that the suburb
                            offers a favourable combination of return potential, liquidity, and market resilience for
                            property investors.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property
                Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 8 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; ">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <h4 class="content-head-sm" style="padding-top:0 !important;margin:0 0 5px;">SUPPLY - <span
                                style="color: #000000;">DEMAND ANALYSIS</span></h4>
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:0 0 5px;">
                            Supply is the number of properties available for use or properties to be sold in the market.
                            Demand is generally the buyers' desire to purchase or rent properties in the market. One of
                            the most fundamental laws of economics is that prices rise when demand exceeds supply. So, a
                            good investment location will have high demand relative to supply.</p>
                        <div class="chart-row" style="margin-bottom: 0px;">
                            <div class="chart-col-4">
                                @if (isset($charts['housePriceChart']) && !empty($charts['housePriceChart']))
                                <img src="{{ $charts['housePriceChart'] }}" alt="img">
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Houses - Sale prices (trend over last 1
                                        yr) – </span> a graph of how the median sale prices of houses have changed in
                                    {{ $suburb }} over the last year
                                </p>
                            </div>
                        </div>
                        <div class="chart-row" style="margin-bottom: 0px;">
                            <div class="chart-col-4">
                                @if (isset($charts['houseListingsChart']) && !empty($charts['houseListingsChart']))
                                <img src="{{ $charts['houseListingsChart'] }}" alt="img">
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Houses – Listings (trend over 1 year) -
                                    </span> a graph of how the number of houses on market have changed in
                                    {{ $suburb }} over
                                    the last year. An increase in listings mean higher supply of houses.
                                </p>
                            </div>
                        </div>
                        <div class="chart-row" style="margin-bottom: 0;">
                            <div class="chart-col-4">
                                @if (isset($charts['houseInventoryChart']) && !empty($charts['houseInventoryChart']))
                                <img src="{{ $charts['houseInventoryChart'] }}" alt="img">
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Houses – inventory –</span> an analysis
                                    of how many months of supply exists. It is measured as the number of properties for
                                    sale compared to how many are being sold. For example, if 100 houses are listed and
                                    25 sell each month, the inventory is 4 months. </p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 9 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; ">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <h4 class="content-head-sm" style="padding-top:0 !important;margin:0 0 5px;">SUPPLY - <span
                                style="color: #000000;">DEMAND ANALYSIS</span></h4>
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:0 0 5px;">
                            Supply is the number of properties available for use or properties to be sold in the market.
                            Demand is generally the buyers' desire to purchase or rent properties in the market. One of
                            the most fundamental laws of economics is that prices rise when demand exceeds supply. So, a
                            good investment location will have high demand relative to supply.</p>
                        <div class="chart-row" style="margin-bottom: 0px;">
                            <div class="chart-col-4">
                                @if (isset($charts['unitPriceChart']) && !empty($charts['unitPriceChart']))
                                <img src="{{ $charts['unitPriceChart'] }}" alt="img">
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Units - Sale prices (trend over last 1
                                        yr) – </span> a graph of how the median sale prices of houses have changed in
                                    {{ $suburb }} over the last year
                                </p>
                            </div>
                        </div>
                        <div class="chart-row" style="margin-bottom: 0px;">
                            <div class="chart-col-4">
                                @if (isset($charts['unitListingsChart']) && !empty($charts['unitListingsChart']))
                                <img src="{{ $charts['unitListingsChart'] }}" alt="img">
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Units – Listings (trend over 1 year) -
                                    </span> a graph of how the number of houses on market have changed in
                                    {{ $suburb }} over
                                    the last year. An increase in listings mean higher supply of houses.
                                </p>
                            </div>
                        </div>
                        <div class="chart-row" style="margin-bottom: 0;">
                            <div class="chart-col-4">
                                @if (isset($charts['unitInventoryChart']) && !empty($charts['unitInventoryChart']))
                                <img src="{{ $charts['unitInventoryChart'] }}" alt="img">
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Units – inventory – </span> an analysis
                                    of how many months of supply exists. It is measured as the number of properties for
                                    sale compared to how many are being sold. For example, if 100 houses are listed and
                                    25 sell each month, the inventory is 4 months. </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 10 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; ">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <h4 class="content-head-sm" style="padding-top:0 !important;margin:0 0 5px;">RENTAL - <span
                                style="color: #000000;">ANALYSIS</span></h4>
                        <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:0 0 5px;">
                            As an investor, cashflow becomes a critical parameter. Hence, understanding how the rental
                            market has been is of utmost importance. Rentals increase when demand for rent exceeds
                            supply of properties. So, a good investment location will have high rental demand relative
                            to supply.</p>
                        <div class="chart-row" style="margin-bottom: 0px;">
                            <div class="chart-col-4">
                                @if (isset($charts['houseRentsChart']) && !empty($charts['houseRentsChart']))
                                <img src="{{ $charts['houseRentsChart'] }}" alt="House Rents Chart">
                                @else
                                <div class="chart-placeholder">House Rents Chart Not Available</div>
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">House rents (trend over 1 year) -
                                    </span> a graph of how the median rent of houses has changed in {{ $suburb }}
                                    over the last
                                    year </p>
                            </div>
                        </div>
                        <div class="chart-row" style="margin-bottom: 0px;">
                            <div class="chart-col-4">
                                @if (isset($charts['unitRentsChart']) && !empty($charts['unitRentsChart']))
                                <img src="{{ $charts['unitRentsChart'] }}" alt="Unit Rents Chart">
                                @else
                                <div class="chart-placeholder">Unit Rents Chart Not Available</div>
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Unit rents (trend over 1 year) –
                                    </span> a graph of how the median rent of units has changed in {{ $suburb }}
                                    over the last
                                    year</p>
                            </div>
                        </div>
                        <div class="chart-row" style="margin-bottom: 0;">
                            <div class="chart-col-4">
                                @if (isset($charts['vacancyRatesChart']) && !empty($charts['vacancyRatesChart']))
                                <img src="{{ $charts['vacancyRatesChart'] }}" alt="Vacancy Rates Chart">
                                @else
                                <div class="chart-placeholder">Vacancy Rates Chart Not Available</div>
                                @endif
                            </div>
                            <div class="chart-col-8">
                                <p style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; "><span
                                        style="color: #ff2969;font-weight:400;">Vacancy rates (trend over 1 year) -
                                    </span> a graph of how the vacancy rates have changed in {{ $suburb }} over
                                    the last year
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 11 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; ">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <h4 class="content-head-sm" style="padding-top:0 !important;margin:0 0 5px;">AFFLUENCY <span
                                style="color: #000000;">INDEX</span></h4>
                        <p
                            style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:0 0 15px;padding:5px 0 0;">
                            An important parameter to measure affluency in an area is Socio-Economic Indexes for Areas
                            (SEIFA), a product developed by the ABS that ranks areas in Australia according to relative
                            socio-economic advantage and disadvantage. The indexes are based on information from the
                            five-yearly Census</p>
                        <div style="margin-bottom: 10px;">
                            @if (isset($charts['map']) && !empty($charts['map']))
                            <img src="{{ $charts['map'] }}" style="width:350px;border-radius:10px" alt="img">
                            @endif
                        </div>
                        <p
                            style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:15px 0 25px;padding:0;">
                            <span style="color: #ff2969;font-weight:bold;">Social index distribution</span> –
                            Percentage of areas in the suburb in different social indexes. More areas in higher ranks
                            mean higher affluency
                        </p>
                        <div style="margin-bottom: 0px;margin-top: 10px;">
                            @if (isset($charts['seifa']) && !empty($charts['seifa']))
                            <img src="{{ $charts['seifa'] }}" style="width:220px;" alt="img">
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 12 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <div class="phone_block">
                    <h4 class="content-head-sm" style="padding-top:0 !important;margin:0 0 5px;">The Next Step Is
                        Knowing Where to Invest</h4>
                    <p
                        style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:0 0 5px;padding:5px 0 0;">
                        PropWealth Next is built for those who see property as strategy, not chance. We don’t predict
                        the market, we read it.</p>
                    <p
                        style="color: #000000; font-size: 10px; line-height: 13px;font-weight:400; margin:0 0 15px;padding: 0;">
                        Every shift, every suburb, every number tells a story about what comes next. Let’s make your
                        next decision an informed one.</p>
                    <div class="phone_no"> 0409 016 393</div>
                    <div class="website_link"><a href="https://propwealth.com.au/"
                            style="color:#000;text-decoration:none;">www.propwealth.com.au</a></div>

                    <p
                        style="color: #000000; font-size: 12px; line-height: 13px;font-weight:bold; margin:0 0 15px;padding:5px 0 0;">
                        PropWealth Next — Invest today in the next GROWTH HOTSPOTS</p>
                </div>
            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 14 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px;">
                <h4 class="city-overview-title">GLOSSARY</h4>

                <!-- Median House Price -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Median House Price
                        </div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The midpoint of all house sale prices in the area. Half the properties sold for less,
                            and
                            half for more. This is a reliable way to track property values because it avoids being
                            skewed by one very high or very low sale.
                        </p>
                    </div>
                </div>

                <!-- Rental Yield -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Rental Yield</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A key measure of return on investment. Calculated as:
                            <span style="margin-left: 15px !important;"><b>Annual Rental Income ÷ Property Value ×
                                    100</b></span>
                            For example, if rent is $500 per week on a $500,000 property, the yield is 5.2%. Higher
                            yields can mean stronger cashflow but may also carry higher risk
                        </p>
                    </div>
                </div>

                <!-- Rental Turnover -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Rental Turnover</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The percentage of rental properties that change tenants within a year. High turnover
                            shows active rental demand and liquidity but may also mean higher management
                            costs or volatility.
                        </p>
                    </div>
                </div>

                <!-- Rental Growth -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Rental Growth</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The rate at which rents are rising (or falling) over time. Investors track this to
                            under-stand whether rental income is likely to increase and keep pace with costs such as
                            interest rates.
                        </p>
                    </div>
                </div>

                <!-- Overall Suburb Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Overall Suburb Score
                        </div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A combined index of desirability, investment potential, and liveability. It brings
                            togeth-er
                            the different scores into one clear snapshot.
                        </p>
                    </div>
                </div>

                <!-- SA4 (Statistical Area Level 4) -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">SA4 (Statistical Area
                            Level 4)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A geographic region defined by the Australian Bureau of Statistics (ABS). Used to
                            measure large-scale population and employment trends.
                        </p>
                    </div>
                </div>

                <!-- PropWealth Investor Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">PropWealth Investor
                            Score
                        </div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A proprietary measure that combines multiple factors. It's designed to give investors a
                            single score summarising long-term investment potential in a suburb.
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property
                Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <!-- Page 15 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px;">

                <!-- PropWealth Investor Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">SALM (Small Area Labour
                            Markets)</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A dataset from the ABS that shows employment types and industries in smaller areas,
                            helping investors understand the local economy and job base.
                        </p>
                    </div>
                </div>

                <!-- Affluency Score  -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Affluency Score</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A rating of a suburb’s socio-economic strength, based on factors like incomes, home
                            ownership, and housing stability. Higher affluency often means lower risk of default
                            and more long-term wealth in the community.
                        </p>
                    </div>
                </div>

                <!-- Affordability Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Sales Turnover</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            Shows how accessible housing is for both buyers and renters. It combines
                        </p>
                    </div>
                </div>

                <!-- Liquidity Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Liquidity Score</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A rating that reflects how easy it is to trade property in a suburb. A high score means
                            buyers and sellers are actively transacting, which reduces risk for investors if they
                            want to exit later.
                        </p>
                    </div>
                </div>

                <!-- Supply Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Median Rent</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            Measures how long it would take to sell all currently listed properties based on sales
                            activity. Low inventory = tighter supply and often rising prices. High inventory can sug
                            -
                            gest oversupply
                        </p>
                    </div>
                </div>

                <!-- Inventory -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Inventory</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The number of properties for sale compared to how many are being sold. For exam
                            -
                            ple, if 100 houses are listed and 25 sell each month, the inventory is 4 months. Lower
                            inventory often supports price growth
                        </p>
                    </div>
                </div>

                <!-- PropWealth Investor Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">SEIFA (Socio-Economic
                            Indexes for Areas)
                        </div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            A proprietary measure that combines multiple factors. It’s designed to give investors
                            a single score summarising long-term investment potential in a suburb.
                        </p>
                    </div>



                </div>
            </div>
            <div class="footer footer-2" style="color: #fff;">
                <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property
                    Investment Outlook
                    2025:<br>Trends, Insights &
                    Growth Potential</p>
                <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
            </div>
        </div>
    </div>
    <!-- Page 15 -->
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px;">

                <!-- Supply -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Supply</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The number of properties available for sale or rent in a given area. When supply is
                            lower than demand, property prices and rents generally rise, boosting returns.
                        </p>
                    </div>
                </div>

                <!-- Demand Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Demand</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The number of buyers or renters actively seeking property in an area. When demand
                            is higher than supply, property prices and rents generally rise, boosting returns.
                        </p>
                    </div>
                </div>

                <!-- Affordability Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Sales Turnover
                        </div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The percentage of all properties in the suburb that are sold within a year. A higher
                            turnover suggests a liquid market, making it easier to buy or sell when needed.
                        </p>
                    </div>
                </div>

                <!-- Cashflow Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Cashflow</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The rental income left over after all expenses (mortgage repayments, maintenance,
                            property management, insurance, etc.). Positive cashflow helps investors build
                            sus-tainable
                            portfolios and reduces reliance on capital growth alone.
                        </p>
                    </div>
                </div>

                <!-- Median Rent Score -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Median Rent</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The middle point of all advertised rents in the area. It shows what a “typical”
                            tenant
                            is
                            paying and helps investors assess affordability and returns.
                        </p>
                    </div>
                </div>

                <!-- Vacancy Rate -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">Inventory</div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            The percentage of rental properties that are unoccupied at a given time. A low
                            vacancy rate means strong rental demand and less risk of lost income. A high
                            vacan-cy
                            rate
                            may signal oversupply or weaker tenant demand
                        </p>
                    </div>
                </div>

                <!-- SEIFA (Socio-Economic Indexes for Areas) -->
                <div class="content-grap-right">
                    <div class="metric-section">
                        <div class="content-head-min"
                            style="display: inline; margin-right: 5px; margin-bottom: 10px;">SEIFA
                            (Socio-Economic
                            Indexes for Areas)
                        </div>
                        <p
                            style="color: #000000; font-size: 11px; line-height: 15px;font-weight:400;padding:0;margin-top: 10px !important;">
                            An ABS index ranking suburbs by socio-economic advantage and disadvantage,
                            based on Census data. A higher SEIFA rank usually indicates wealthier and more
                            stable communities.
                        </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="footer footer-2" style="color: #fff;">
            <p class="footer-address" style="padding-left:30px;">{{ $suburb }} Property
                Investment Outlook
                2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>

</body>

</html>