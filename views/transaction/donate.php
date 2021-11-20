<?php
/**
 * @var \yii\web\View $this
 * @var \app\models\Transaction $model
 */
?>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Оплата</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="7bIfPtKZr5mghPVWRyONKGM2cC8r2eiuOv5zxZjv">
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="/css/donate_style.css" type="text/css" media="all">
</head>
<body>

<div class="d-none"></div>
<div id="skeleton" style="display: none;">
    <div class="header_skeleton tui-skeleton"></div>
    <div class="container">
        <div class="mobile_block_skeleton tui-skeleton"></div>
        <div class="_floating_skeleton tui-skeleton"></div>
        <div class="info_skeleton tui-skeleton"></div>
    </div>
    <div class="tabs_skeleton tui-skeleton"></div>
    <div class="container ">
        <div class="row">
            <div class="col-lg-9 col-12">
                <div class="card_skeleton">
                    <div class="card_skeleton_side card_skeleton_front">
                        <div class="card_skeleton_pan tui-skeleton"></div>
                        <div class="card_skeleton_row">
                            <div class="card_skeleton_date tui-skeleton"></div>
                            <div class="card_skeleton_icons__all">
                                <div class="card_skeleton_icons__icon tui-space_right-3 tui-skeleton"></div>
                                <div class="card_skeleton_icons__icon tui-space_right-3 tui-skeleton"></div>
                                <div class="card_skeleton_icons__icon tui-skeleton"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card_skeleton_side card_skeleton_back">
                        <div class="card_skeleton_line tui-skeleton"></div>
                        <div class="card_skeleton_code tui-skeleton"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="email_skeleton tui-skeleton"></div>
        <div class="send_button_skeleton tui-skeleton"></div>
    </div>
</div>
<div class="page-wrap" style="display: block;">
    <div class="main">
        <div class="details">
            <div class="container">
                <div>
                    <a class="collapsed" data-toggle="collapse" href="#" aria-expanded="false">
                        <span>
                        <?=$model->user->country == \app\helpers\CountryHelper::COUNTRY_UKRAINE ? $model->sum_uah : $model->sum_rub?><span class="decimals">,00 <?=$model->user->country == \app\helpers\CountryHelper::COUNTRY_UKRAINE ? "грн" : "руб"?></span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container">
            <form id="payment_form" class="needs-validation" novalidate="" method="post">
                <div class="cc">
                    <input type="hidden" value="<?=$model->id?>" name="merchant_order_id" id="transactionId">
                    <input type="hidden" name="amount" value="<?=$model->sum_rub?>" id="amount">
                    <input type="hidden" name="country" value="<?=\app\helpers\CountryHelper::getShortName()[$model->user->country]?>" id="country">
                    <input type="hidden" name="api_key" value="<?=\app\models\Config::get(\app\models\Config::VAR_PAYS_PUBLIC_KEY)?>" id="api_key">
                    <div class="card-side card-front">
                        <div class="card-brand">
                            <div class="brand-logo"></div>
                            <div class="brand-name"></div>
                        </div>
                        <div class="card-number input-wrap">
                            <input name="card_number" id="card_number" type="text" tabindex="0" inputmode="numeric" required="">
                            <label>Номер карты</label>
                            <span class="cleaner"><svg focusable="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><g id="tuiIconCloseLarge" xmlns="http://www.w3.org/2000/svg" transform="translate(-12,-12)"><svg x="50%" y="50%"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
<path fill="currentColor" d="M12,10.8l3-3c0.3-0.3,0.9-0.3,1.2,0c0.3,0.3,0.3,0.9,0,1.2l-3,3l3,3c0.3,0.3,0.3,0.9,0,1.2
	c-0.3,0.3-0.9,0.3-1.2,0l-3-3l-3,3c-0.3,0.3-0.9,0.3-1.2,0c-0.3-0.3-0.3-0.9,0-1.2l3-3l-3-3c-0.3-0.3-0.3-0.9,0-1.2
	c0.3-0.3,0.9-0.3,1.2,0C9,7.8,12,10.8,12,10.8z"></path>
</svg>
</svg></g></svg></span>
                        </div>
                        <div class="error-message">Проверьте номер карты</div>
                        <div class="expiry-area d-flex justify-content-between">
                            <div class="expiry input-wrap">
                                <input name="card" id="expiry" type="text" tabindex="0" inputmode="numeric" data-hint="00/00" required="">
                                <label>Месяц</label>
                            </div>
                            <div class="d-flex align-items-center payment-system-logos">
                                <div class="possible show">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" fill-rule="nonzero">
                                            <path fill="#FFF" fill-opacity=".01" d="M0 0h32v32H0z"></path>
                                            <path fill="#CBCFD3" d="M11.257 21.302l1.618-9.143h2.587l-1.619 9.143h-2.586zm11.969-8.918A6.909 6.909 0 0 0 20.907 12c-2.556 0-4.357 1.24-4.372 3.017-.014 1.314 1.286 2.047 2.267 2.485 1.007.448 1.346.734 1.34 1.134-.006.613-.803.893-1.547.893-1.036 0-1.586-.139-2.435-.48l-.334-.146-.363 2.047c.605.255 1.722.477 2.882.488 2.72 0 4.485-1.226 4.505-3.124.01-1.04-.68-1.832-2.172-2.484-.904-.423-1.458-.705-1.452-1.134 0-.38.469-.786 1.481-.786a4.93 4.93 0 0 1 1.937.35l.231.106.351-1.982zm6.634-.216h-1.999c-.62 0-1.083.162-1.355.758l-3.842 8.38h2.717s.444-1.127.545-1.375l3.313.004c.077.32.315 1.37.315 1.37h2.4l-2.094-9.137zm-3.19 5.893c.214-.527 1.03-2.556 1.03-2.556-.014.024.213-.53.344-.873l.175.789.599 2.64H26.67zM9.087 12.166L6.554 18.4l-.27-1.267c-.471-1.461-1.94-3.044-3.582-3.836l2.316 7.996 2.737-.003 4.073-9.125h-2.74l-.001.001z"></path>
                                            <path fill="#CBCFD3" d="M4.205 12.16H.033L0 12.35c3.246.757 5.393 2.586 6.285 4.784l-.908-4.202c-.156-.58-.61-.752-1.172-.772"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="possible show">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" fill-rule="evenodd">
                                            <path fill="none" d="M0 0h32v32H0z"></path>
                                            <circle cx="15.72" cy="16.79" r="6.12" fill="#CBCFD3"></circle>
                                            <path fill="#E7E8EA" d="M15.686 10.671a7.789 7.789 0 0 0-2.97 6.114c0 2.485 1.17 4.71 2.97 6.114a7.774 7.774 0 0 1-4.813 1.664c-4.314 0-7.805-3.478-7.805-7.778 0-4.3 3.49-7.778 7.805-7.778 1.821 0 3.49.627 4.813 1.664zm12.732 6.114c0 4.3-3.49 7.778-7.804 7.778a7.774 7.774 0 0 1-4.813-1.664 7.724 7.724 0 0 0 2.97-6.114 7.789 7.789 0 0 0-2.97-6.114 7.774 7.774 0 0 1 4.813-1.664c4.314 0 7.804 3.5 7.804 7.778z"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="possible show">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" fill-rule="nonzero">
                                            <path fill="#FFF" fill-opacity=".01" d="M0 0h32v32H0z"></path>
                                            <path fill="#CBCFD3" d="M28.408 12h-6.073c.326 2.024 2.285 3.918 4.44 3.918h4.833c.065-.196.065-.457.065-.653A3.234 3.234 0 0 0 28.408 12zM22.857 16.245v4.898h2.939V18.53h2.612c1.437 0 2.678-.98 3.07-2.286h-8.62l-.001.001zM12.408 12v9.143h2.612s.653 0 .98-.653l2.286-4.572h.326v5.225h2.939V12h-2.612s-.653.065-.98.653l-2.286 4.571h-.326V12h-2.939zM0 21.143V12h2.939s.849 0 1.306 1.306c1.175 3.461 1.306 3.918 1.306 3.918s.261-.848 1.306-3.918C7.314 12 8.163 12 8.163 12h2.939v9.143H8.163v-4.898h-.326l-1.633 4.898H4.898l-1.633-4.898H2.94v4.898H0z"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div data-index-4="" class="selected">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" fill-rule="evenodd">
                                            <path fill="#F50" fill-opacity="0" d="M0 0h32v32H0z"></path>
                                            <g fill-rule="nonzero">
                                                <path fill="#FFF" fill-opacity=".01" d="M0 0h32v32H0z"></path>
                                                <path fill="#0057A0" d="M11.257 21.302l1.618-9.143h2.587l-1.619 9.143h-2.586zm11.969-8.918A6.909 6.909 0 0 0 20.907 12c-2.556 0-4.357 1.24-4.372 3.017-.014 1.314 1.286 2.047 2.267 2.485 1.007.448 1.346.734 1.34 1.134-.006.613-.803.893-1.547.893-1.036 0-1.586-.139-2.435-.48l-.334-.146-.363 2.047c.605.255 1.722.477 2.882.488 2.72 0 4.485-1.226 4.505-3.124.01-1.04-.68-1.832-2.172-2.484-.904-.423-1.458-.705-1.452-1.134 0-.38.469-.786 1.481-.786a4.93 4.93 0 0 1 1.937.35l.231.106.351-1.982zm6.634-.216h-1.999c-.62 0-1.083.162-1.355.758l-3.842 8.38h2.717s.444-1.127.545-1.375l3.313.004c.077.32.315 1.37.315 1.37h2.4l-2.094-9.137zm-3.19 5.893c.214-.527 1.03-2.556 1.03-2.556-.014.024.213-.53.344-.873l.175.789.599 2.64H26.67zM9.087 12.166L6.554 18.4l-.27-1.267c-.471-1.461-1.94-3.044-3.582-3.836l2.316 7.996 2.737-.003 4.073-9.125h-2.74l-.001.001z"></path>
                                                <path fill="#FAA61A" d="M4.205 12.16H.033L0 12.35c3.246.757 5.393 2.586 6.285 4.784l-.908-4.202c-.156-.58-.61-.752-1.172-.772"></path>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                                <div data-index-5="" class="selected">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" fill-rule="nonzero">
                                            <path fill="#FFF" fill-opacity=".01" d="M0 0h32v32H0z"></path>
                                            <path fill="#FBB735" d="M16 23.47c2.211-1.467 3.667-3.967 3.667-6.803 0-2.837-1.456-5.337-3.667-6.804a8.26 8.26 0 0 1 4.583-1.378c4.557 0 8.25 3.663 8.25 8.182 0 4.518-3.693 8.181-8.25 8.181A8.26 8.26 0 0 1 16 23.471v-.001z"></path>
                                            <path fill="#F03727" d="M16 9.863c-2.211 1.467-3.667 3.967-3.667 6.804 0 2.836 1.456 5.336 3.667 6.804a8.26 8.26 0 0 1-4.583 1.377c-4.557 0-8.25-3.663-8.25-8.181 0-4.519 3.693-8.182 8.25-8.182A8.26 8.26 0 0 1 16 9.863z"></path>
                                            <path fill="#F6772D" d="M16 9.863c2.211 1.467 3.667 3.967 3.667 6.804 0 2.836-1.456 5.336-3.667 6.804-2.211-1.468-3.667-3.968-3.667-6.804 0-2.837 1.456-5.337 3.667-6.804z"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div data-index-6="" class="selected">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                        <g fill="none" fill-rule="nonzero">
                                            <path fill="#FFF" fill-opacity=".01" d="M0 0h32v32H0z"></path>
                                            <path fill="#0AADFC" d="M16 23.634a8.325 8.325 0 0 0 3.704-6.93c0-2.89-1.47-5.435-3.704-6.93a8.333 8.333 0 1 1 0 13.86z"></path>
                                            <path fill="#F03727" d="M16 9.774a8.325 8.325 0 0 0-3.704 6.93A8.324 8.324 0 0 0 16 23.634a8.333 8.333 0 1 1 0-13.86z"></path>
                                            <path fill="#726ECC" d="M16 9.774a8.325 8.325 0 0 1 3.704 6.93A8.324 8.324 0 0 1 16 23.634a8.325 8.325 0 0 1-3.704-6.93c0-2.89 1.47-5.435 3.704-6.93z"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div> </div>
                    </div>
                    <div class="card-side card-back">
                        <div class="card-stripe">
                        </div>
                        <div class="cvc input-wrap">
                            <input id="cvc" name="card_cvc" tabindex="0" inputmode="numeric" required="" autocomplete="off">
                            <label>Код</label>
                            <div class="cvc-hint hint" data-external_content="#hint_cvc" data-original-title="" title="">
                                <svg focusable="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16"><g id="tuiIconTooltipFill" xmlns="http://www.w3.org/2000/svg" transform="translate(-8,-8)"><svg x="50%" y="50%"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                                <path fill="currentColor" d="M8,15c-3.9,0-7-3.1-7-7s3.1-7,7-7s7,3.1,7,7S11.9,15,8,15z M7.3,11.5c0,0.4,0.3,0.7,0.7,0.7
	c0.4,0,0.7-0.3,0.7-0.7c0-0.4-0.3-0.7-0.7-0.7C7.6,10.8,7.3,11.1,7.3,11.5z M6.6,5.9c0,0,0-0.2,0.1-0.3C6.9,5.4,7.3,5.2,8,5.2
	c0.3,0,0.5,0,0.7,0.1c0.5,0.1,0.7,0.4,0.7,1.3c0,0.2-0.2,0.5-0.5,0.7C8.7,7.5,8.5,7.6,8.3,7.7c-0.6,0.3-1,1.1-1,1.7
	c0,0.4,0.3,0.7,0.7,0.7c0.4,0,0.7-0.3,0.7-0.7C8.7,9.3,8.8,9,9,9c0.2-0.1,0.5-0.3,0.8-0.5c0.6-0.5,1-1.1,1-1.8
	c0-1.5-0.7-2.4-1.8-2.7C8.7,3.8,8.4,3.8,8,3.8c-1.1,0-1.9,0.4-2.4,1C5.3,5.2,5.2,5.6,5.2,5.9c0,0.4,0.3,0.7,0.7,0.7
	C6.3,6.6,6.6,6.3,6.6,5.9C6.6,5.9,6.6,5.9,6.6,5.9z"></path>
                                            </svg>
                                        </svg></g></svg> </div>
                        </div>
                        <div class="m-card-shadow"></div>
                    </div>
                </div>
                <div class="error-area"></div>
                <div class="m-error-area error-area" id="m-error-area"></div>
                <div class="actions submit-area">
                    <button class="btn" type="submit">
                        <span class="initial-text">Оплатить</span>
                        <span class="loading-content">
                        <span class="loader"><svg _ngcontent-ipd-c30="" automation-id="tui-loader__loader" class="icon" focusable="false" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle _ngcontent-ipd-c30="" class="circle" cx="50" cy="50" r="50" stroke-dasharray="314"></circle></svg></span>
                        <span class="loading-text">Отправка данных</span>
                        </span>
                    </button>
                </div>
                <input type="hidden" name="key" id="key" value="">
            </form>
        </div>
        <div id="hint_cvc" class="hint-content">
            <p>Трехзначный номер на обратной<br> стороне вашей карты:</p>
            <div class="cvc-image"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="/js/jquery.inputmask.min.js"></script>
<script type="text/javascript" src="/js/donate_functions.js"></script>
<script>
</script>
</body></html>