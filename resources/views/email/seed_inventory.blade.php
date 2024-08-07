<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>PhilRice Seed Inventory as of {{date('F d, Y')}}</title>
	<style>
		/* -------------------------------------
			GLOBAL RESETS
		------------------------------------- */

		/*All the styling goes here*/

		img {
			border: none;
			-ms-interpolation-mode: bicubic;
			max-width: 100%;
		}

		body {
			background-color: #f6f6f6;
			font-family: sans-serif;
			-webkit-font-smoothing: antialiased;
			font-size: 14px;
			line-height: 1.4;
			margin: 0;
			padding: 0;
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		table {
			border-collapse: separate;
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
			width: 100%; }
			table td {
				font-family: sans-serif;
				font-size: 14px;
				vertical-align: top;
			}

		/* -------------------------------------
			BODY & CONTAINER
		------------------------------------- */

		.body {
			background-color: #f6f6f6;
			width: 100%;
		}

		/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
		.container {
			display: block;
			margin: 0 auto !important;
			/* makes it centered */
			max-width: 1400px;
			padding: 10px;
			width: 1400px;
			max-height: 500px; 
			overflow-y: scroll;
		}

		/* This should also be a block element, so that it will fill 100% of the .container */
		.content {
			box-sizing: border-box;
			display: block;
			margin: 0 auto;
			max-width: 1400px;
			padding: 10px;
		}

		/* -------------------------------------
			HEADER, FOOTER, MAIN
		------------------------------------- */
		.main {
			background: #ffffff;
			border-radius: 3px;
			width: 100%;
		}

		.wrapper {
			box-sizing: border-box;
			padding: 20px;
		}

		.content-block {
			padding-bottom: 10px;
			padding-top: 10px;
		}

		.footer {
			clear: both;
			margin-top: 10px;
			text-align: center;
			width: 100%;
		}
		.footer td,
		.footer p,
		.footer span,
		.footer a {
			color: #999999;
			font-size: 12px;
			text-align: center;
		}

		/* -------------------------------------
			TYPOGRAPHY
		------------------------------------- */
		h1,
		h2,
		h3,
		h4 {
			color: #000000;
			font-family: sans-serif;
			font-weight: 400;
			line-height: 1.4;
			margin: 0;
			margin-bottom: 30px;
		}

		h1 {
			font-size: 35px;
			font-weight: 300;
			text-align: center;
			text-transform: capitalize;
		}

		p,
		ul,
		ol {
			font-family: sans-serif;
			font-size: 14px;
			font-weight: normal;
			margin: 0;
			margin-bottom: 15px;
		}
		p li,
		ul li,
		ol li {
			list-style-position: inside;
			margin-left: 5px;
		}

		a {
			color: #3498db;
			text-decoration: underline;
		}

		/* -------------------------------------
			BUTTONS
		------------------------------------- */
		.btn {
			box-sizing: border-box;
			width: 100%; }
			.btn > tbody > tr > td {
				padding-bottom: 15px; }
				.btn table {
					width: auto;
				}
				.btn table td {
					background-color: #ffffff;
					border-radius: 5px;
					text-align: center;
				}
				.btn a {
					background-color: #ffffff;
					border: solid 1px #3498db;
					border-radius: 5px;
					box-sizing: border-box;
					color: #3498db;
					cursor: pointer;
					display: inline-block;
					font-size: 14px;
					font-weight: bold;
					margin: 0;
					padding: 12px 25px;
					text-decoration: none;
					text-transform: capitalize;
				}

				.btn-primary table td {
					background-color: #3498db;
				}

				.btn-primary a {
					background-color: #3498db;
					border-color: #3498db;
					color: #ffffff;
				}

		/* -------------------------------------
			OTHER STYLES THAT MIGHT BE USEFUL
		------------------------------------- */
		.last {
			margin-bottom: 0;
		}

		.first {
			margin-top: 0;
		}

		.align-center {
			text-align: center;
		}

		.align-right {
			text-align: right;
		}

		.align-left {
			text-align: left;
		}

		.clear {
			clear: both;
		}

		.mt0 {
			margin-top: 0;
		}

		.mb0 {
			margin-bottom: 0;
		}

		.preheader {
			color: transparent;
			display: none;
			height: 0;
			max-height: 0;
			max-width: 0;
			opacity: 0;
			overflow: hidden;
			mso-hide: all;
			visibility: hidden;
			width: 0;
		}

		.powered-by a {
			text-decoration: none;
		}

		hr {
			border: 0;
			border-bottom: 1px solid #f6f6f6;
			margin: 20px 0;
		}

		/* -------------------------------------
			RESPONSIVE AND MOBILE FRIENDLY STYLES
		------------------------------------- */
		@media only screen and (max-width: 620px) {
			table[class=body] h1 {
				font-size: 28px !important;
				margin-bottom: 10px !important;
			}
			table[class=body] p,
			table[class=body] ul,
			table[class=body] ol,
			table[class=body] td,
			table[class=body] span,
			table[class=body] a {
				font-size: 16px !important;
			}
			table[class=body] .wrapper,
			table[class=body] .article {
				padding: 10px !important;
			}
			table[class=body] .content {
				padding: 0 !important;
			}
			table[class=body] .container {
				padding: 0 !important;
				width: 100% !important;
			}
			table[class=body] .main {
				border-left-width: 0 !important;
				border-radius: 0 !important;
				border-right-width: 0 !important;
			}
			table[class=body] .btn table {
				width: 100% !important;
			}
			table[class=body] .btn a {
				width: 100% !important;
			}
			table[class=body] .img-responsive {
				height: auto !important;
				max-width: 100% !important;
				width: auto !important;
			}
		}

		/* -------------------------------------
			PRESERVE THESE STYLES IN THE HEAD
		------------------------------------- */
		@media all {
			.ExternalClass {
				width: 100%;
			}
			.ExternalClass,
			.ExternalClass p,
			.ExternalClass span,
			.ExternalClass font,
			.ExternalClass td,
			.ExternalClass div {
				line-height: 100%;
			}
			.apple-link a {
				color: inherit !important;
				font-family: inherit !important;
				font-size: inherit !important;
				font-weight: inherit !important;
				line-height: inherit !important;
				text-decoration: none !important;
			}
			#MessageViewBody a {
				color: inherit;
				text-decoration: none;
				font-size: inherit;
				font-family: inherit;
				font-weight: inherit;
				line-height: inherit;
			}
			.btn-primary table td:hover {
				background-color: #34495e !important;
			}
			.btn-primary a:hover {
				background-color: #34495e !important;
				border-color: #34495e !important;
			}
		}

		.details_tbl {
			border-collapse: collapse;
		}

		.details_tbl, .details_tbl_data {
			border: 1px solid black;
			padding: 5px;
		}

		.center {
			display: block;
			margin: 0 auto;
		}
	</style>
</head>
<body class="">
	<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
		<tr>
			<td>&nbsp;</td>
			<td class="container">

				<div class="content">

					<!-- START CENTERED WHITE CONTAINER -->
					<table role="presentation" class="main">
						<tr><td class="wrapper" style="background-color: #006b33;"><p style="color: white; font-size: 20px; margin: 0px;">PhilRice Seed Inventory as of {{date('F d, Y')}}</p></td></tr>
						<!-- START MAIN CONTENT AREA -->
						<tr>
							<td class="wrapper">
								<table role="presentation" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td>

											<table style="border: 1px solid black; border-collapse:  collapse; text-align: center;">
												<thead style="border: 1px solid black; text-align: center;">
													<tr style="border: 1px solid black; text-align: center;">
														<th style="border: 1px solid black; width: 10%;">PhilRice Branch</th>
														<th style="border: 1px solid black; width: 10%; text-align: center;">Variety</th>
														<th style="border: 1px solid black; width: 10%; text-align: center;">Semester Harvested</th>
														<th style="border: 1px solid black; width: 20%; text-align: center;" colspan="3">Seed Class/Qty (kg)</th>
														<th style="border: 1px solid black; width: 25%; text-align: center;" colspan="3">Last Update</th>
														<th style="border: 1px solid black; width: 25%; text-align: center;" colspan="3">Last Updated By</th>
													</tr>
													<tr style="border: 1px solid black; text-align: center;">
														<th style="border: 1px solid black;"></th>
														<th style="border: 1px solid black;"></th>
														<th style="border: 1px solid black;"></th>
														<th style="border: 1px solid black; text-align: center;">FS</th>
														<th style="border: 1px solid black; text-align: center;">RS</th>
														<th style="border: 1px solid black; text-align: center;">CS</th>
														<th style="border: 1px solid black; text-align: center;">FS</th>
														<th style="border: 1px solid black; text-align: center;">RS</th>
														<th style="border: 1px solid black; text-align: center;">CS</th>
														<th style="border: 1px solid black; text-align: center;">FS</th>
														<th style="border: 1px solid black; text-align: center;">RS</th>
														<th style="border: 1px solid black; text-align: center;">CS</th>
													</tr>
												</thead>
												<tbody style="border: 1px solid black; text-align: center;">
													@foreach($data as $key => $value)
														@if(!empty($value))
															<tr style="border: 1px solid black; text-align: left;">
																<th colspan="12" style="border: 1px solid black;">{{$key}}</th>
															</tr>

															@foreach ($value as $item)
																@if ($item['foundation'] != 0 || $item['registered'] != 0 || $item['certified'] != 0)
																	<tr style="border: 1px solid black; text-align: center;">
																		<td style="border: 1px solid black; text-align: center;"></td>
																		<td style="border: 1px solid black;">{{$item['variety']}}</td>
																		<td style="border: 1px solid black;">{{$item['year_harvested']}}  {{$item['semester_harvested']}}</td>
																		<td style="border: 1px solid black; text-align: right;">{{$item['foundation']}}</td>
																		<td style="border: 1px solid black; text-align: right;">{{$item['registered']}}</td>
																		<td style="border: 1px solid black; text-align: right;">{{$item['certified']}}</td>
																		<td style="border: 1px solid black; font-size: 10px;">{{$item['foundation_last_update']}}</td>
																		<td style="border: 1px solid black; font-size: 10px;">{{$item['registered_last_update']}}</td>
																		<td style="border: 1px solid black; font-size: 10px;">{{$item['certified_last_update']}}</td>
																		<td style="border: 1px solid black; font-size: 10px;">{{$item['foundation_last_updated_by']}}</td>
																		<td style="border: 1px solid black; font-size: 10px;">{{$item['registered_last_updated_by']}}</td>
																		<td style="border: 1px solid black; font-size: 10px;">{{$item['certified_last_updated_by']}}</td>
																	</tr>
																@endif
															@endforeach
														@endif
													@endforeach
												</tbody>
											</table>

										</td>
									</tr>
									<tr>
										<td>
											<br>

											<p style="text-align: center;"><a href="https://rsis.philrice.gov.ph">https://rsis.philrice.gov.ph</a></p>
											<br>
											<p><i>NOTE:</i></p>
											<div style="border-style: solid; border-width: .5px; padding: 5px;">
												<p><i>Depending on your email provider and your computer’s configuration settings, inbound messages generated by the system may be incorrectly tagged as spam and delivered to your <b>JUNK/SPAM</b> folder</i></p>
											</div>
											<br>
											<p><i>*** This is an automatically generated email - please do not reply to it. If you have any queries regarding the convention please email it at <a href="mailto:rsis.bpi.philrice@gmail.com">rsis.bpi.philrice@gmail.com</a> or contact us using this link <a href="https://rsis.philrice.gov.ph/helpdesk">Contact Us</a> ***</i></p>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<!-- END MAIN CONTENT AREA -->
					</table>
					<!-- END CENTERED WHITE CONTAINER -->
				</div>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>
</body>
</html>