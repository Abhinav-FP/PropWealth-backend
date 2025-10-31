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
            top: 17%;
            left: 34%;
            padding: 10mm;
            color: #fff;
            border-radius: 15px;
            text-align: left;
            font-size: 28px;
            line-height: 34px;
            font-weight: 300;
            width: 60%;
        }

        .content-2 {
            left: 7%;
            top: 14%;
            /* Moved higher to give more space */
            width: 80%;
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
            font-size: 23px;
            line-height: 65px;
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
        @if (file_exists(public_path('Image/page1_optimized.jpg')))
            .page-1-bg-base64 {
                background-image: url("data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('Image/page1_optimized.jpg'))) }}");
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                background-color: #2c3e50;
            }
        @endif

        @if (file_exists(public_path('Image/page2_optimized.jpg')))
            .page-2-bg-base64 {
                background-image: url("data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('Image/page2_optimized.jpg'))) }}");
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                background-color: #34495e;
            }
        @endif

        @if (file_exists(public_path('Image/page6_optimized.jpg')))
            .page-3-bg-base64,
            .page-4-bg-base64 {
                background-image: url("data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('Image/page6_optimized.jpg'))) }}");
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                background-color: #ecf0f1;
            }
        @endif

        .chart-container {
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
            <div class="content">
                <p>{{ $suburb }} Property<br>Investment Outlook<br>{{ $year }}: Trends, Insights<br>and
                    Growth Potential</p>
                <p class="date">{{ $date }}</p>
            </div>
        </div>
        <div class="footer">
            <p>215/33LexingtonDr,<br>BellaVistaNSW 2153</p>
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
                    <li>City - introduction</li>
                    <li>Scores</li>
                    <li>Demand-supply - houses (trend of price, inventory)</li>
                    <li>Demand-supply - units (trend of price, inventory)</li>
                    <li>Rental analysis</li>
                    <li>Social index</li>
                    <li>Glossary / FAQs</li>
                    <li>CTA</li>
                </ol>
            </div>
        </div>
        <div class="footer footer-2">
            <p class="footer-address">{{ $suburb }} Property Investment Outlook 2025:<br>Trends, Insights &
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
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <p>
                    @if (isset($houseText) && !empty($houseText))
                        {{ $houseText }}
                    @endif
                </p>

                <p>
                    @if (isset($unitText) && !empty($unitText))
                        {{ $unitText }}
                    @endif
                </p>

                <div class="content-grap-right">
                    <div class="chart-container">
                        <div class="chart-row">
                            <div class="chart-item">
                                <h4>House Inventory Trends</h4>
                                @if (isset($charts['houseInventoryChart']) && !empty($charts['houseInventoryChart']))
                                    <img src="{{ $charts['houseInventoryChart'] }}" alt="House Inventory Chart">
                                @else
                                    <div class="chart-placeholder">Chart data unavailable</div>
                                @endif
                            </div>
                            <div class="chart-item">
                                <h4>House Listings Analysis</h4>
                                @if (isset($charts['houseListingsChart']) && !empty($charts['houseListingsChart']))
                                    <img src="{{ $charts['houseListingsChart'] }}" alt="House Listings Chart">
                                @else
                                    <div class="chart-placeholder">Chart data unavailable</div>
                                @endif
                            </div>
                        </div>

                        <div class="chart-row">
                            <div class="chart-item" style="width: 100%;">
                                <h4>House Price Trends</h4>
                                @if (isset($charts['housePriceChart']) && !empty($charts['housePriceChart']))
                                    <img src="{{ $charts['housePriceChart'] }}" alt="House Price Chart"
                                        style="width: 80%; max-width: 500px;">
                                @else
                                    <div class="chart-placeholder">Chart data unavailable</div>
                                @endif
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
            <p class="footer-address">{{ $suburb }} Property Investment Outlook 2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>


    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['unitInventoryChart']) && !empty($charts['unitInventoryChart']))
                                    <img src="{{ $charts['unitInventoryChart'] }}" alt="">
                                @endif
                            </div>
                            <div class="chart-item">
                                @if (isset($charts['unitInventoryChart']) && !empty($charts['unitInventoryChart']))
                                    <img src="{{ $charts['unitInventoryChart'] }}" alt="">
                                @endif

                            </div>
                        </div>

                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['unitListingsChart']) && !empty($charts['unitListingsChart']))
                                    <img src="{{ $charts['unitListingsChart'] }}" alt="" style="width: 60%;">
                                @endif
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
            <p class="footer-address">{{ $suburb }} Property Investment Outlook 2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>


    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <div class="chart-row">
                            <div class="chart-item">

                                @if (isset($charts['unitPriceChart']) && !empty($charts['unitPriceChart']))
                                    <img src="{{ $charts['unitPriceChart'] }}" alt="">
                                @endif

                            </div>
                            <div class="chart-item">

                                @if (isset($charts['houseRentsChart']) && !empty($charts['houseRentsChart']))
                                    <img src="{{ $charts['houseRentsChart'] }}" alt="">
                                @endif
                            </div>
                        </div>

                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['unitRentsChart']) && !empty($charts['unitRentsChart']))
                                    <img src="{{ $charts['unitRentsChart'] }}" alt="" style="width: 60%;">
                                @endif

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
            <p class="footer-address">{{ $suburb }} Property Investment Outlook 2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>


    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['vacancyRatesChart']) && !empty($charts['vacancyRatesChart']))
                                    <img src="{{ $charts['vacancyRatesChart'] }}" alt="">
                                @endif
                            </div>
                            <div class="chart-item">
                                @if (isset($charts['housePriceSegments']) && !empty($charts['housePriceSegments']))
                                    <img src="{{ $charts['housePriceSegments'] }}" alt="">
                                @endif
                            </div>
                        </div>

                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['housePriceSegments']) && !empty($charts['housePriceSegments']))
                                    <img src="{{ $charts['housePriceSegments'] }}" alt=""
                                        style="width: 60%;">
                                @endif
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
            <p class="footer-address">{{ $suburb }} Property Investment Outlook 2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
    <div class="page page-3-bg page-3-bg-base64 page3">
        <div class="content-wrapper content-wrapper-2">
            <div class="header-2">
                <span>{{ $year }}</span>
            </div>
            <div class="content content-2" style="color: #000000; font-size: 14px; line-height: 21px;">
                <div class="content-grap-right">
                    <div class="chart-container">
                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['vacancyRatesChart']) && !empty($charts['vacancyRatesChart']))
                                    <img src="{{ $charts['vacancyRatesChart'] }}" alt="">
                                @endif
                            </div>
                            <div class="chart-item">
                                @if (isset($charts['housePriceSegments']) && !empty($charts['housePriceSegments']))
                                    <img src="{{ $charts['housePriceSegments'] }}" alt="">
                                @endif
                            </div>
                        </div>

                        <div class="chart-row">
                            <div class="chart-item">
                                @if (isset($charts['housePriceSegments']) && !empty($charts['housePriceSegments']))
                                    <img src="{{ $charts['housePriceSegments'] }}" alt=""
                                        style="width: 60%;">
                                @endif
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
            <p class="footer-address">{{ $suburb }} Property Investment Outlook 2025:<br>Trends, Insights &
                Growth Potential</p>
            <p><a href="https://propwealth.com.au/" style="color: #fff;">www.propwealth.com.au</a></p>
        </div>
    </div>
</body>

</html>
