<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Dragonfly</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha512-bnIvzh6FU75ZKxp0GXLH9bewza/OIw6dLVh9ICg0gogclmYGguQJWl8U30WpbsGTqbIiAwxTsbe76DErLq5EDQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/css/bootstrap.min.css" integrity="sha512-T584yQ/tdRR5QwOpfvDfVQUidzfgc2339Lc8uBDtcp/wYu80d7jwBgAxbyMh0a9YM9F8N3tdErpFI8iaGx6x5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css" integrity="sha256-46qynGAkLSFpVbEBog43gvNhfrOj+BmwXdxFgVK/Kvc=" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.2.1/dist/umd/popper.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.1/js/bootstrap.min.js" integrity="sha512-UR25UO94eTnCVwjbXozyeVd6ZqpaAE9naiEUBK/A+QDbfSTQFhPGj5lOR6d8tsgbBk84Ggb5A3EkjsOgPRPcKA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="/assets/tween.js"></script>
		<link rel="icon" href="data:;base64,iVBORw0KGgo=">
		<style>
            @media only screen and (min-width: 578px) {
                .to-toggle {
                    overflow-x: hidden;
                }
                .stall-ticker-container {
                    min-width: 14rem;
                }
                .account-ticker-container {
                    min-width: 16rem;
                }
                .date-ticker-container {
                    min-width: 16rem;
                }
            }
            @media only screen and (max-width: 577px) {
                .stall-ticker-container {
                    min-width: 0rem;
                }
                .account-ticker-container {
                    min-width: 0rem;
                }
                .date-ticker-container {
                    min-width: 0rem;
                }
                .to-toggle.hide {
                    min-width: 13rem;
                }
                .to-toggle {
                    max-width: 0px;
                    overflow-x: hidden;
                    min-width: 0px;
                }
				div.control-panel {
					display: none;
				}
            }
			body {
				background-color: #cce0ff;
				color: #000;
				margin: 0;
			}
			a {
				color: #080;
                box-shadow: none;
			}
            .left-panel-internal a, .left-panel-internal a:focus {
                box-shadow: none;
            }
			canvas { display: block; }
            .click-to-create {
                box-shadow: none;
            }
            .btn-menu {
                color: var(--primary);
                transition: all 150ms;
                box-shadow: none;
            }
            .btn-menu:hover {
                /* color: #ffffff; */
                background: #eaeaea;
            }
			.load-game-screen {
				height: 100vh;
				width: 100vw;
				position: fixed;
				z-index: 1101;
				opacity: 1;
				background: white;
				transition: opacity 200ms ease-in-out;
			}
            .load-game-screen.hide {
				opacity: 0;
			}
            .start-new-game, .continue-saved-game {
                transition: max-height 200ms ease-in-out, padding 200ms ease-in-out;
                overflow-y: hidden;
                padding: 0.75rem;
            }
            .load-game-screen.new .start-new-game {
                max-height: 0px;
                padding: 0rem;
            }
            .load-game-screen.continue .continue-saved-game {
                max-height: 0px;
                padding: 0rem;
            }
			.loading-msg {
				height: 100vh;
				width: 100vw;
				position: fixed;
				z-index: 1100;
				opacity: 1;
				background: white;
				transition: opacity 200ms ease-in-out;
			}
			.loading-msg.hide {
				opacity: 0;
			}
			.loading-text {
				color: #636363;
				display: inline-block;
				text-align: center;
				padding: 0rem 0rem 0.5rem 0rem;
			}
			.progress, .loading-text {
				max-height: 100px;
				transition: all 100ms ease-in-out;
				overflow-y: hidden;
			}
			.loading-msg.initialising .progress {
				max-height: 0px;
				padding: 0rem 0rem 0rem 0rem;
				transition: all 200ms ease-in-out;
			}
			.loading-msg.initialising .loading-text {
				max-height: 0px;
				transition: all 200ms 100ms ease-in-out;
				padding: 0rem 0rem 0rem 0rem;
			}
			.loading-cog {
				color: #09abe8;
				max-height: 0px;
				overflow: hidden;
			}
			.loading-msg.initialising .loading-cog {
				max-height: 100px;
				transition: all 200ms 300ms ease-in-out;
			}
			.loading-msg .progress {
				width: 20rem;
				padding: 0.5rem 0rem 0.5rem 0rem;
			}
			.control-panel {
				position: fixed;
				display: inline-block;
				bottom: 0;
				left: 0;
				max-height: 4rem;
				padding: 0px;
				overflow-y: hidden;
			}
			.ticker-container {
				position: fixed;
				/* display: block; */
				bottom: 0;
				right: 0;
				padding: 0px;
				overflow-y: hidden;
                display: none;
			}
			.menu-container {
				transition: transform 200ms 50ms ease-in-out;
				transform: translateY(0vh);
			}
			.menu-container.hide {
				transition: transform 200ms 50ms ease-in-out;
				transform: translateY(100vh);
			}
			.begin-2d-shape-capture {
				transition: all 200ms 50ms ease-in-out;
			}
			.begin-2d-shape-capture.hide {
				transition: all 200ms 50ms ease-in-out;
				max-height: 0px;
			}
			.stop-2d-shape-capture {
				transition: all 200ms 50ms ease-in-out;
			}
			.stop-2d-shape-capture.hide {
				transition: all 200ms 50ms ease-in-out;
				max-height: 0px;
			}
			a.btn i {
				background: #00000010;
				min-width: 3rem;
			}
			a i::before {
				vertical-align: sub;
			}
            /* i.fa-dollar-sign:before, i.fa-clock:before {
                vertical-align: sub;
            } */
            .day-ticker, .market-status-ticker, .stall-count-ticker {
                max-height: 23px;
            }
            .market-ticker-container, .market-ticker-container small {
                transition: all 150ms ease-in-out;
            }
			.hide-toggle {
				/* transition: transform 200ms ease-in-out; */
				transform: translateX(0vw);
			}
			.hide-toggle.hide {
				/* transition: transform 200ms ease-in-out; */
				transform: translateX(100vw);
				max-height: 0px;
				max-width: 0px;
			}
			.right-panel {
				position: fixed;
				top: 0;
				right: 0;
				min-width: 20rem;
				max-width: 20rem;
				transition: transform 200ms 100ms ease-in-out;
				transform: translateX(0vw);
			}
			.right-panel.hide {
				transform: translateX(100vw);
				transition: transform 200ms 0ms ease-in-out;
			}
			.products div {
				border-bottom: 1px solid #00000020;
			}
			.ticker-container {
				/* min-width: 25rem; */
			}
            .card.left-menu {
                min-width: 20rem;
                bottom: 0;
                left: 0;
                box-shadow: 0px 0px 0px 0px #00000030;
                position: fixed;
                overflow-x: hidden;
                height: 100vh;
                transition: transform 400ms ease-in-out, box-shadow 400ms ease-in-out;
                transform: translateY(100vh);
            }
            .card.left-menu.show {
                transform: translateY(0vh);
                box-shadow: 0px 0px 24px 0px #00000030;
            }
            .left-menu .left-panel-internal {
                overflow-y: scroll;
                overflow-x: hidden;
            }
            .control-panel {
                transition: transform 400ms ease-in-out;
                transform: translateY(0vh);
            }
            .control-panel.hide {
                transition: transform 400ms ease-in-out;
                transform: translateY(100vh);
            }
            .open-stall, .closed-stall {
                display: flex;
            }
            .open-stall.hide, .closed-stall.hide {
                display: none;
            }
            .new-stall-container {
                transition: max-height 200ms ease-in-out;
                overflow-y: hidden;
                max-height: 600px;
            }
            .new-stall-container.hide {
                max-height: 0px;
            }
            .new-stall-category-btn {
                transition: max-height 200ms ease-in-out;
                overflow-y: hidden;
                max-height: 100px;
            }
            .new-stall-category-btn.hide {
                max-height: 0px;
            }
            .darker {
                background: #00000030;
                min-width: 3rem;
            }
			.game-screen-options {
				transition: max-height 200ms 100ms ease-in-out, margin 200ms 100ms ease-in-out;
                overflow-y: hidden;
                max-height: 500px;
			}
			.game-screen-options.hide {
				/* transition: max-height 200ms 100ms ease-in-out, margin 200ms 100ms ease-in-out; */
				transition: max-height 200ms ease-in-out, margin 200ms ease-in-out;
                max-height: 0px;
			}
		</style>
		<script>
		function clip(text) {
	  		var copyElement = document.createElement('input');
			copyElement.setAttribute('type', 'text');
	  		copyElement.setAttribute('value', text);
	  		copyElement = document.body.appendChild(copyElement);
	  		copyElement.select();
	  		document.execCommand('copy');
	  		copyElement.remove();
	  	}
		function consoleLog(text) {
			console.log(text);
			$(".console-ticker").html(text);
		}
		window.onload = (e) => {
			$(".game-screen-options").toggleClass("hide mt-3");
		}
		</script>
	</head>

	<body>
		<div class="load-game-screen d-flex flex-column align-items-center new continue">
			<div class="main-logo mb-2 mt-auto">
				<i class="fas fa-store fa-3x text-warning"></i>
			</div>
			<h3 class="mt-2">
				Market Operator Tycoon
			</h3>
			<div class="d-flex flex-row game-screen-options loading justify-content-center mt-3 w-100 text-black-50">
	            <span class="loading mr-2">Loading</span>
				<span>
					<i class="fas fa-spinner fa-spin fa-fw fa-lg"></i>
				</span>
			</div>
			<div class="d-flex flex-column game-screen-options mb-auto finished-loading hide align-items-center">
	            <a href="#" class="rounded-0 border-0 btn btn-primary start-new-game m-1">Start new game</a>
	            <a href="#" class="rounded-0 border-0 btn btn-warning text-white continue-saved-game m-1">Continue saved game</a>
			</div>
		</div>
		<div class="loading-msg d-flex flex-column align-items-center">
			<div class="loading-cog mb-2 mt-auto">
				<i class="fas fa-cog fa-spin fa-3x"></i>
			</div>
			<h5 class="loading-text">
				0%
			</h5>
			<div class="progress">
				<div class="progress-bar progress-bar-striped progress-bar-animated loading-progress" role="progressbar" style="width: 0%;" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<h5 class="loading mb-auto mt-2">
				Loading models
			</h5>
		</div>
		<div class="right-panel hide">
			<div class="card badge closed-stall hide">
				<h5 class="p-3 mb-0 d-flex flex-column">
					<span class="card-title">Empty space</span>
					<small class="text-muted">Location: <span class="locname text-center"></span></small>
				</h5>
                <a href="#" class="btn btn-menu rounded-0 border-0 d-flex align-content-stretch p-0 w-100 locate-stall-btn">
                    <span class="py-3 px-3">Look at</span>
                    <i class="py-3 px-3 fas fa-crosshairs fa-fw ml-auto"></i>
                </a>
				<h6 class="p-3 mb-0 d-flex flex-column">
					<span class="card-title">Build stall</span>
					<small class="text-muted">Select a category</small>
				</h6>
				<div class="d-flex flex-column">
                    <a href="#" data-cid="1" class="btn btn-menu new-stall-category-btn rounded-0 border-0 d-flex align-content-stretch p-0 w-100">
                        <span class="py-3 px-3">Food & Drinks</span>
                        <i class="py-3 px-3 fas fa-utensils fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-cid="2" class="btn btn-menu new-stall-category-btn rounded-0 border-0 d-flex align-content-stretch p-0 w-100">
                        <span class="py-3 px-3">Fruit & Vegetables</span>
                        <i class="py-3 px-3 fas fa-carrot fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-cid="3" class="btn btn-menu new-stall-category-btn rounded-0 border-0 d-flex align-content-stretch p-0 w-100">
                        <span class="py-3 px-3">Art</span>
                        <i class="py-3 px-3 fas fa-palette fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-cid="15" class="btn btn-menu new-stall-category-btn rounded-0 border-0 d-flex align-content-stretch p-0 w-100">
                        <span class="py-3 px-3">Provisions</span>
                        <i class="py-3 px-3 fas fa-palette fa-fw ml-auto"></i>
                    </a>
				</div>
				<div class="d-flex flex-column new-stall-container hide">
                    <a href="#" data-cid="0" class="btn btn-menu new-stall-build-btn rounded-0 border-0 d-flex flex-column align-content-stretch p-0 w-100 stall-1">
                        <div class="d-flex align-content-stretch w-100 p-0">
                            <span class="py-3 px-3 name"></span>
                            <span class="py-3 px-3 price ml-auto"></span>
                        </div>
                        <div class="d-flex flex-column w-100 mb-3 text-black-50" style="font-size: small;">
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column mx-1 w-50">
                                    <span class="title my-1">Customer satisfaction</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-primary progress-bar progress-bar-striped satisfaction-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column mx-1 w-50">
                                    <span class="title my-1">Product margin</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-success progress-bar progress-bar-striped margin-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column w-50 mx-1">
                                    <span class="title my-1">Running costs</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-warning progress-bar progress-bar-striped costs-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column w-50 mx-1">
                                    <span class="title my-1">Customer appeal</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-info progress-bar progress-bar-striped appeal-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" data-cid="1" class="btn btn-menu new-stall-build-btn rounded-0 border-0 d-flex flex-column align-content-stretch p-0 w-100 stall-2">
                        <div class="d-flex align-content-stretch w-100 p-0">
                            <span class="py-3 px-3 name"></span>
                            <span class="py-3 px-3 price ml-auto"></span>
                        </div>
                        <div class="d-flex flex-column w-100 mb-3 text-black-50" style="font-size: small;">
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column mx-1 w-50">
                                    <span class="title my-1">Customer satisfaction</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-primary progress-bar progress-bar-striped satisfaction-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column mx-1 w-50">
                                    <span class="title my-1">Product margin</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-success progress-bar progress-bar-striped margin-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column w-50 mx-1">
                                    <span class="title my-1">Running costs</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-warning progress-bar progress-bar-striped costs-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column w-50 mx-1">
                                    <span class="title my-1">Customer appeal</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-info progress-bar progress-bar-striped appeal-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" data-cid="2" class="btn btn-menu new-stall-build-btn rounded-0 border-0 d-flex flex-column align-content-stretch p-0 w-100 stall-3">
                        <div class="d-flex align-content-stretch w-100 p-0">
                            <span class="py-3 px-3 name"></span>
                            <span class="py-3 px-3 price ml-auto"></span>
                        </div>
                        <div class="d-flex flex-column w-100 mb-3 text-black-50" style="font-size: small;">
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column mx-1 w-50">
                                    <span class="title my-1">Customer satisfaction</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-primary progress-bar progress-bar-striped satisfaction-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column mx-1 w-50">
                                    <span class="title my-1">Product margin</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-success progress-bar progress-bar-striped margin-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <div class="d-flex flex-column w-50 mx-1">
                                    <span class="title my-1">Running costs</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-warning progress-bar progress-bar-striped costs-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column w-50 mx-1">
                                    <span class="title my-1">Customer appeal</span>
                                    <div class="progress my-1 rounded-0">
                                        <div class="bg-info progress-bar progress-bar-striped appeal-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
				</div>
			</div>
			<div class="card badge open-stall">
				<div class="category text-center p-3 text-white bg-primary"></div>
				<h5 class="p-3 mb-0 d-flex flex-column">
					<span class="client-name card-title text-truncate" style="max-width:18rem;"></span>
					<small class="contact-name text-muted"></small>
				</h5>
                <div class="d-flex flex-column w-100 mb-3 text-black-50" style="font-size: small;">
                    <div class="d-flex flex-row">
                        <div class="d-flex flex-column mx-1 w-50">
                            <span class="title my-1">Customer satisfaction</span>
                            <div class="progress my-1 rounded-0">
                                <div class="bg-primary progress-bar progress-bar-striped satisfaction-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mx-1 w-50">
                            <span class="title my-1">Product margin</span>
                            <div class="progress my-1 rounded-0">
                                <div class="bg-success progress-bar progress-bar-striped margin-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-row">
                        <div class="d-flex flex-column w-50 mx-1">
                            <span class="title my-1">Running costs</span>
                            <div class="progress my-1 rounded-0">
                                <div class="bg-warning progress-bar progress-bar-striped costs-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="d-flex flex-column w-50 mx-1">
                            <span class="title my-1">Customer appeal</span>
                            <div class="progress my-1 rounded-0">
                                <div class="bg-info progress-bar progress-bar-striped appeal-pr" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="#" class="btn btn-menu rounded-0 border-0 d-flex align-content-stretch p-0 w-100 locate-stall-btn">
                    <span class="py-3 px-3">Look at</span>
                    <i class="py-3 px-3 fas fa-crosshairs fa-fw ml-auto"></i>
                </a>
				<h6 class="p-3 mb-0 d-flex flex-column">
					<span class="card-title">Products</span>
					<small class="sales"></small>
				</h6>
				<div class="px-3 pt-0 pb-3 d-flex flex-column w-100 products">
					<div class="d-flex flex-row product-0 my-2">
						<div class="name text-primary text-truncate">

						</div>
						<div class="price text-black-50 ml-auto">

						</div>
					</div>
					<div class="d-flex flex-row product-1 my-2">
						<div class="name text-primary text-truncate">

						</div>
						<div class="price text-black-50 ml-auto">

						</div>
					</div>
					<div class="d-flex flex-row product-2 my-2">
						<div class="name text-primary text-truncate">

						</div>
						<div class="price text-black-50 ml-auto">

						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="card left-menu d-flex flex-column rounded-0 align-items-center border-0">
            <div class="d-flex flex-column left-panel-internal w-100 show flex-fill">
                <h5 class="d-flex flex-row align-items-center my-2 px-3">
                    <span class="">Game</span>
                    <!-- <i class="ml-auto fas fa-angle-up fa-fw fa-sm light-green"></i> -->
                </h5>
                <div class="d-flex flex-column w-100 product-options-container mb-2">
                    <div class="d-flex flex-column align-items-start">
                        <a href="#" class="btn btn-menu rounded-0 border-0 new-game-btn d-flex align-content-stretch p-0 w-100">
                            <span class="py-1 px-3">New game</span>
                            <i class="py-1 fas fa-gamepad fa-fw ml-auto"></i>
                        </a>
                        <a href="#" class="btn btn-menu rounded-0 border-0 save-game-to-server d-flex align-content-stretch p-0 w-100">
                            <span class="py-1 px-3">Save game</span>
                            <i class="py-1 far fa-save fa-fw ml-auto"></i>
                        </a>
                    </div>
                </div>
                <h5 class="d-flex flex-row align-items-center mb-2 px-3">
                    <span class="">Camera</span>
                    <!-- <i class="ml-auto fas fa-angle-up fa-fw fa-sm light-green"></i> -->
                </h5>
                <div class="d-flex flex-column w-100 product-options-container mb-2">
                    <div class="d-flex flex-column align-items-start">
                        <a href="#" class="btn btn-menu rounded-0 border-0 lock-camera-btn d-flex align-content-stretch p-0 w-100">
                            <span class="py-1 px-3">Lock</span>
                            <i class="py-1 fas fa-lock fa-fw ml-auto"></i>
                        </a>
                        <a href="#" class="btn btn-menu rounded-0 border-0 auto-rotate-btn d-flex align-content-stretch p-0 w-100">
                            <span class="py-1 px-3">Auto rotate</span>
                            <i class="py-1 fas fa-sync-alt fa-fw ml-auto"></i>
                        </a>
                    </div>
                </div>
                <h5 class="mb-2 px-3">
                    Shape
                </h5>
                <div class="d-flex flex-column align-items-start mb-2">
                    <a href="#" class="hide-toggle btn btn-menu rounded-0 border-0 begin-2d-shape-capture d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Capture 2D</span>
                        <i class="py-1 fas fa-vector-square fa-fw ml-auto"></i>
                    </a>
                    <a href="#" class="hide-toggle btn btn-menu rounded-0 border-0 stop-2d-shape-capture d-flex align-content-stretch p-0 w-100 hide">
                        <span class="py-1 px-3 points-text">Copy points</span>
                        <i class="py-1 fa-fw fas fa-copy points-icon ml-auto"></i>
                    </a>
                </div>
                <h5 class="mb-2 px-3">
                    <span>Add</span>
                    <small class="text-muted">Click to create</small>
                </h5>
                <div class="d-flex flex-column align-items-start">
                    <a href="#" data-type="npc" class="btn btn-menu rounded-0 border-0 click-to-create d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Wooden ball</span>
                        <i class="py-1 fas fa-volleyball-ball fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-type="banana" class="btn btn-menu rounded-0 border-0 click-to-create d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Banana</span>
                        <i class="py-1 fas fa-bomb fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-type="pallet" class="btn btn-menu rounded-0 border-0 click-to-create d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Pallets</span>
                        <i class="py-1 fas fa-stream fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-type="chair" class="btn btn-menu rounded-0 border-0 click-to-create d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Chair</span>
                        <i class="py-1 fas fa-cube fa-fw ml-auto"></i>
                    </a>
                    <a href="#" data-type="table" class="btn btn-menu rounded-0 border-0 click-to-create d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Table</span>
                        <i class="py-1 far fa-circle fa-fw ml-auto"></i>
                    </a>
                    <a href="#" class="btn btn-menu rounded-0 border-0 flip-gravity-toggle d-flex align-content-stretch p-0 w-100">
                        <span class="py-1 px-3">Flip gravity</span>
                        <i class="py-1 far fa-arrow-alt-circle-up fa-fw ml-auto"></i>
                    </a>
                </div>
            </div>
            <!-- <a class="rounded-0 w-100 btn-primary py-1 align-items-baseline border-0 justify-content-center btn d-flex menu-down">
                <i class="fas fa-angle-down fa-fw fa-sm p-3 text-white" style="background:transparent;"></i>
            </a> -->
        </div>
		<div class="control-panel">
			<div class="controls">
				<a href="#" class="btn btn-primary rounded-0 border-0 open-menu p-0 d-flex align-content-stretch"><i class="fas fa-angle-up fa-fw p-3"></i></a>
			</div>
		</div>
		<div class="bg-white ml-auto d-flex flex-row ticker-container" style="border-top-left-radius: 0.5rem;">
            <div class="d-flex align-content-stretch p-0 bg-white account-ticker-container">
                <div class="d-flex flex-row flex-fill to-toggle hide">
                    <h6 class="d-flex flex-column m-0 px-3 flex-fill justify-content-center">
                        <small class="mx-auto text-black-50 sales-balance-ticker text-center"></small>
                        <small class="mx-auto font-weight-bold text-center">Sales</small>
                    </h6>
                    <h6 class="d-flex flex-column m-0 px-3 flex-fill justify-content-center">
                        <small class="mx-auto text-black-50 account-balance-ticker text-center"></small>
                        <small class="mx-auto font-weight-bold text-center">Balance</small>
                    </h6>
                </div>
                <a href="#" class="toggle-div darker p-3 d-flex justify-content-center align-items-center">
                    <i class="fas fa-dollar-sign fa-fw text-black-50"></i>
                </a>
            </div>
			<div class="d-flex align-content-stretch p-0 bg-secondary stall-ticker-container">
                <div class="d-flex flex-row flex-fill to-toggle">
                    <h6 class="d-flex flex-column m-0 px-3 flex-fill justify-content-center">
                        <small class="mx-auto text-white-50 stall-count-ticker text-center"></small>
                        <small class="mx-auto text-white-50 customer-count-ticker text-center"></small>
                    </h6>
                    <h6 class="d-flex flex-column m-0 px-3 flex-fill justify-content-center">
                        <small class="stall-word-ticker mx-auto text-center font-weight-bold"></small>
                        <small class="customer-word-ticker mx-auto text-center font-weight-bold"></small>
                    </h6>
                </div>
                <a href="#" class="toggle-div darker p-3 d-flex justify-content-center align-items-center">
    				<i class="fas fa-store fa-fw text-black-50"></i>
                </a>
			</div>
			<div class="d-flex align-content-stretch p-0 market-ticker-container bg-success date-ticker-container">
                <div class="d-flex flex-row flex-fill to-toggle">
                    <h6 class="d-flex flex-column m-0 px-3 flex-fill justify-content-center">
                        <small class="mx-auto text-white-50 date-ticker text-center"></small>
                        <small class="mx-auto text-white-50 time-ticker text-center"></small>
                    </h6>
                    <h6 class="d-flex flex-column m-0 px-3 flex-fill justify-content-center">
                        <small class="day-ticker mx-auto text-center font-weight-bold"></small>
                        <small class="market-status-ticker mx-auto text-center font-weight-bold"></small>
                    </h6>
                </div>
                <a href="#" class="toggle-div darker p-3 d-flex justify-content-center align-items-center">
    				<i class="fas fa-clock fa-fw text-black-50"></i>
                </a>
			</div>
		</div>
		<div id="container"></div>

		<script src="/assets/node_modules/three/examples/js/libs/ammo.js"></script>
		<script type="module">


            import * as THREE from '/assets/node_modules/three/build/three.module.js';

            import Stats from '/assets/node_modules/three/examples/jsm/libs/stats.module.js';
            import { GUI } from '/assets/node_modules/three/examples/jsm/libs/dat.gui.module.js';

            import { OrbitControls } from '/assets/node_modules/three/examples/jsm/controls/OrbitControls.js';
            import { OBJLoader } from '/assets/node_modules/three/examples/jsm/loaders/OBJLoader.js';
            import { DDSLoader } from '/assets/node_modules/three/examples/jsm/loaders/DDSLoader.js';
            import { MTLLoader } from '/assets/node_modules/three/examples/jsm/loaders/MTLLoader.js';
            import { FBXLoader } from '/assets/node_modules/three/examples/jsm/loaders/FBXLoader.js';
			import { BufferGeometryUtils } from '/assets/node_modules/three/examples/jsm/utils/BufferGeometryUtils.js';

            var cameraCenter = new THREE.Vector2(0,0);

            var npcs = new THREE.Group();
            var bananas = new THREE.Group();

            var water, waterNormals;

            // var definitions

            // time related
            var defaultFont;
            var isTriggerSunrise = true;
            var isTriggerSunset = true;
            var isSundown = false;

            var gameId, savedStalls, gameTime, savedCameraTarget, savedCameraPosition;
            var marketCustomers = [];
            var marketCustomersBuying = [];
            var marketCustomersLeaving = [];
            var gameTimeUnit = 1;
            var auto_save_frame_trigger = 200;
            var gameTimeMultiplier = 0;
            var lastSave;
            var saveFile = {};
            var localClients = false;

            // chances
            var cust_enter_market_chc = 0.04;
            var cust_leave_market_chc = 0.04;

            var market_status;
            var clients = [];
            var marker;
            var buildingSelectFrame,stallSelectFrame;
            var ctween;
            var toiletSignTx;
			var fruit_stand;
			var park_bench;
			var skybox, skyboxObj;
            var lightOrbit, lightOrbitControls, lightCamera;
			var controls;
			var circle_radius = 160.00;
			var masterPositions, masterProducts, masterClients;

            var marketLogoTextures = [];

			var fabrics = [];

            var chalkboardTextures = [];

            var windmill, windmillBlades, windmillVertical, windmills;

            var dropoff_text, pickup_text;
            var toiletBlock, toiletBlocks;

			var totalSales = 0;
			var npc_speed = 2;
			var grasses = [];
			var concreteTextures = [];
			var woodTextures = [];
			var bananaTextures = [];
			var highlightTextures = [];
			var cars = [];
			var banana_obj;
            var bricks = [];
            var bricks_bm = [];
            var stones = [];
            var roads = [];
            var road_markings = [];
			var concrete_bm = [];
			var windowTextures = [];
			var floorGroup;

            var pallets, pallet;

			var pathTextures = [];

			var params = {
				enableWind: true
			};

			var rayCaster = new THREE.Raycaster();
			var mousePosition = new THREE.Vector2();

			var stall, seq;
			var stalls = [];
            var stalls_group = new THREE.Group();
            var lightpoles_group = new THREE.Group();
            lightpoles_group.name = 'lightpoles';
            var lightpole;


			var TIMESTEP = 18 / 1000;
			var TIMESTEP_SQ = TIMESTEP * TIMESTEP;

			var tmpForce = new THREE.Vector3();

			var lastTime;

            //utility functions
			function rndArray(arr) {
				return arr[Math.floor(Math.random()*arr.length)];
			}

            function rndNeg(n) {
                return (Math.random()*n*2)-n;
            }

            function rndPos(n) {
                return Math.floor(Math.random()*n);
            }

			function _fcur(num) {
				return formatMoney(parseFloat(num), 2, ".", ",");
			}

            function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
              try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
              } catch (e) {
                // console.log(e)
              }
            };

            //game functions

            function triggerSunrise() {
                light.tweens.sunrise.start();
            }

            function triggerSunset() {
                light.tweens.sunset.start();
                saveFile.stats.accountBalance = parseFloat(saveFile.stats.salesBalance)+parseFloat(saveFile.stats.accountBalance);
                saveFile.stats.salesBalance = 0;
            }

			// Graphics variables
			var container, stats;
			var camera, controls, scene, renderer;
			var textureLoader;
			var clock = new THREE.Clock();
			var clickRequest = false;
			var mouseCoords = new THREE.Vector2();
			var ballMaterial = new THREE.MeshPhongMaterial( { color: 0x202020 } );
			var pos = new THREE.Vector3();
			var quat = new THREE.Quaternion();

			// Physics variables
			var gravityConstant = - 9.80;
			var physicsWorld;
			var rigidBodies = [];
			var softBodies = [];
			var margin = 0.05;
			var transformAux1;
			var softBodyHelpers;
            var light, atmosphericLight, sunCamera;

            var chalkboardBase;


            //textures
            textureLoader = new THREE.TextureLoader();

            //texture and material utility functions
            function setTexture(tx={}) {

                if(tx.texture!==undefined) {
                    var texture = tx.texture.clone();
                } else {
                    var texture = new THREE.Texture();
                }

                if(tx.offsetx!=undefined && tx.offsety!=undefined) {
                    texture.offset.set(tx.offsetx,tx.offsety);
                }

                if(tx.reptx!=undefined && tx.repty!=undefined) {
                    texture.repeat.set( tx.reptx, tx.repty );
                }

                if(tx.anisotropy!=undefined) {
                    texture.anisotropy = tx.anisotropy;
                }
                texture.wrapT = THREE.RepeatWrapping;
                texture.wrapS = THREE.RepeatWrapping;
                texture.needsUpdate = true;

                return texture;

            }

            function setTx(tx={}) {
                return setTexture(tx);
            }

            function setMtl(mtl) {
                var mtls = new THREE.MeshPhongMaterial( mtl );
                return mtls;
            }

            function setBmMtls(tx, bmtx, specular, shininess, bumpScale) {
                var mtls = new THREE.MeshPhongMaterial( {
                    map: tx,
                    side: THREE.DoubleSide,
                    specular: specular,
                    shininess: shininess,
                    bumpMap: bmtx,
                    bumpScale: bumpScale
                });
                return mtls;
            }

            function setMtls(t, reptx, repty, bm=false) {

                var mtls_texture = t.clone();
                mtls_texture.needsUpdate = true;
                mtls_texture.wrapT = THREE.RepeatWrapping;
                mtls_texture.wrapS = THREE.RepeatWrapping;
                mtls_texture.repeat.set( reptx, repty );
                mtls_texture.offset.set(0.3,0.5);
                // mtls_texture.anisotropy = 2;
                // mtls_texture.encoding = THREE.sRGBEncoding;

                if(!bm) {
                    var mtls = new THREE.MeshPhongMaterial( {
                        map: mtls_texture,
                        side: THREE.DoubleSide
                    } );
                } else {
                    var bm_texture = bm.clone();
                    bm_texture.needsUpdate = true;
                    bm_texture.wrapT = THREE.RepeatWrapping;
                    bm_texture.wrapS = THREE.RepeatWrapping;
                    bm_texture.repeat.set( reptx, repty );
                    bm_texture.offset.set(0.3,0.5);
                    var mtls = new THREE.MeshPhongMaterial( {
                    map: mtls_texture,
                    side: THREE.DoubleSide,
					// color: 0x552811,
					specular: 0x6e6e6e,
					shininess: 2.5,
					bumpMap: bm_texture,
					bumpScale: 0.2
				} );
                }
                mtls.flatShading = false;
                return mtls;
            }

            //--------------------------------------------------------------------------------------------
            //Load physics engine, check for saved game
            //--------------------------------------------------------------------------------------------

			Ammo().then( function ( AmmoLib ) {

				Ammo = AmmoLib;

                //Load game screen is at highest layer, determine whether to start a new game or load a saved one
                checkCookie();
                //

			} );

            //--------------------------------------------------------------------------------------------

            //cookie control
            function createCookie(cookieName,cookieValue,daysToExpire) {
                var date = new Date();
                date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
                document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
            }
            function deleteCookie(cookieName) {
                document.cookie = cookieName + "= ; expires=Thu, 01 Jan 1970 00:00:00 GMT";
            }
            function accessCookie(cookieName) {
                var name = cookieName + "=";
                var allCookieArray = document.cookie.split(';');
                for(var i=0; i<allCookieArray.length; i++) {
                    var temp = allCookieArray[i].trim();
                    if (temp.indexOf(name)==0) {
                        return temp.substring(name.length,temp.length);
                    }
                }
            }
            function checkCookie() {
                var id = accessCookie("mot_id");
                if (id!==undefined) {
                    //load saved game
                    load_saved_game(id);
                } else {
                    $(".load-game-screen").addClass("continue");
                }
                $(".load-game-screen").removeClass("new");
            }

            //new game
            function start_new_game() {

                //new game defaults
                saveFile = {};
                saveFile.settings = {};
                saveFile.stats = {};

                saveFile.stats.startTime = Date.now();
                saveFile.stats.gameTime = 0;
                saveFile.stats.accountBalance = 25000;
                saveFile.settings.savedCameraPosition = { x:40.00, y:50.00, z:100.00 };
                saveFile.settings.savedCameraTarget = { x:0.00, y:0.00, z:0.00 };

                $.ajax({
                    url: "/new-game",
                    type: 'post',
                    data: { save:JSON.stringify(saveFile) },
                    dataType: 'json'
                }).done(function(data) {

                    createCookie("mot_id", data.game.motId, 7);

                    saveFile = data.game.motData;
                    localClients = data.game.motClientData;
                    savedStalls = data.game.motStallData;

                    // console.log(saveFile);

                    //--------------------------------------------------------------------------------------------
                    //Begin 3d environment initialisation chain
                    //--------------------------------------------------------------------------------------------

                    preLoadMTL();

                    //--------------------------------------------------------------------------------------------

                    //hide load game screen
                    $(".load-game-screen").addClass("hide");
                    setTimeout(function(){ $(".load-game-screen").remove() }, 600);

                });
            }



            //load saved game
            function load_saved_game(id) {

                $.ajax({
                    url: "/load-game",
                    type: 'post',
                    data: { id:id },
                    dataType: 'json'
                }).done(function(data) {

                    // console.log(data);

                    if(data.motData!=undefined) {
                        deleteCookie("mot_id");
                        createCookie("mot_id", id, 7);
                        saveFile = data.motData;
                        localClients = data.motClientData;
                        savedStalls = data.motStallData;
                        consoleLog("Game data loaded.");
                        $(".load-game-screen").removeClass("continue");
                    } else {
                        deleteCookie("mot_id");
                        checkCookie();
                    }

                });
            }

            //save game
            function save_game_to_server() {

                var data = {};
                data.settings = {};
                data.stats = {};
                data.gameId = saveFile.gameId;

                data.stats.startTime = saveFile.stats.startTime;
                data.stats.gameTime = saveFile.stats.gameTime;
                data.stats.accountBalance = saveFile.stats.accountBalance;
                data.settings.savedCameraPosition = {x:camera.position.x,y:camera.position.y,z:camera.position.z};
                data.settings.savedCameraTarget = {x:controls.target.x,y:controls.target.y,z:controls.target.z};
                data.savedStalls = null;
                data.clients = localClients;
                stalls_group.children.forEach((st) => {
                    if(st.info.status=='open') {
                        if(!data.savedStalls) {
                            data.savedStalls = [];
                        }
                        var stall = {};
                        stall.sales = st.info.sales;
                        stall.cust = st.info.cust;
                        stall.clientId = st.client._clid;
                        stall.locId = st.locId;
                        stall.time = st.info.time;
                        data.savedStalls.push(stall);
                    }
                });
                $.ajax({
                    url: "/save-game",
                    type: 'post',
                    data: { save:JSON.stringify(data) },
                    dataType: 'json'
                }).done(function(data) {

                    if(data.status==1) {
                        consoleLog("Game saved.");
                    } else {
                        consoleLog("Game not saved correctly.");
                    }

                    lastSave = saveFile.stats.gameTime;

                });
            }

            //save game button UI control
            $(".save-game-to-server").on("click", function(){
                // $(".save-game-to-server i").removeClass("fa-save far").addClass("fas fa-spinner");
                save_game_to_server();
            });

            //save game button UI control
            $(".flip-gravity-toggle").on("click", function(){
                gravityConstant = (-gravityConstant);
                physicsWorld.setGravity( new Ammo.btVector3( 0, gravityConstant, 0 ) );
                physicsWorld.getWorldInfo().set_m_gravity( new Ammo.btVector3( 0, gravityConstant, 0 ) );
                $(this).find("i").toggleClass("fa-arrow-alt-circle-up fa-arrow-alt-circle-down");
                $(this).toggleClass("btn-menu btn-warning text-white");
            });

            function getClient(cid) {
                for (var i in masterClients) {
                    if (parseInt(masterClients[i].MarketClientId) == parseInt(cid)) {
                        return masterClients[i];
                    }
                }
                return false;
            }

            function getProductName(pid) {
                for (var i in masterProducts) {
                    if (parseInt(masterProducts[i].pid) == parseInt(pid)) {
                        return masterProducts[i].pname;
                    }
                }
                return "Unknown product";
            }

            //Start game button action
            $(".demo-game").on("click", function(){

                load_saved_game(20);

                while(saveFile.stats == undefined) {

                }

                preLoadMTL();

                //hide load game screen
                $(".load-game-screen").addClass("hide");
                setTimeout(function(){ $(".load-game-screen").remove() }, 600);


            });
            $(".start-new-game").on("click", function(){

                start_new_game();

            });
            $(".continue-saved-game").on("click", function(){

                //--------------------------------------------------------------------------------------------
                //Begin 3d environment initialisation chain
                //--------------------------------------------------------------------------------------------

                preLoadMTL();

                //--------------------------------------------------------------------------------------------

                //hide load game screen
                $(".load-game-screen").addClass("hide");
                setTimeout(function(){ $(".load-game-screen").remove() }, 600);

            });

            //preload models, materials and textures
            function preLoadMTL() {

                var loading_msg = document.querySelector(".loading-msg");
                var loading_text = document.querySelector(".loading-text");


                var onProgress = function ( url, itemsloaded=1, itemstotal=1 ) {

                        var percentComplete = 0;
                        if(itemstotal!=0) { percentComplete = itemsloaded / itemstotal * 100; }

                        loading_text.innerHTML = Math.round( percentComplete, 2 ) + '%';

                        $(".loading-progress").css("width", percentComplete+"%");

                };

                var onError = function () { };

                var manager = new THREE.LoadingManager();
                manager.addHandler( /\.dds$/i, new DDSLoader() );
                manager.onProgress = onProgress;

                var textureLoader = new THREE.TextureLoader( manager );

                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard0-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard1-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard2-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard3-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard4-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard5-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard6-min.jpg' ) );
                chalkboardTextures.push( textureLoader.load( '/assets/textures/chalkboards/chalkboard7-min.jpg' ) );
                fabrics.push( textureLoader.load( '/assets/textures/rsz_fabric0-min.png' ) );
                fabrics.push( textureLoader.load( '/assets/textures/rsz_fabric1-min.png' ) );
                fabrics.push( textureLoader.load( '/assets/textures/rsz_fabric2-min.jpg' ) );
                fabrics.push( textureLoader.load( '/assets/textures/rsz_fabric3-min.jpg' ) );
                fabrics.push( textureLoader.load( '/assets/textures/rsz_fabric4-min.jpg' ) );
                grasses.push( textureLoader.load( '/assets/textures/rsz_grass0-min.jpg' ) );
                grasses.push( textureLoader.load( '/assets/textures/rsz_grass1-min.jpg' ) );
                grasses.push( textureLoader.load( '/assets/textures/rsz_grass2-min.jpg' ) );
                concrete_bm.push( textureLoader.load( '/assets/textures/rsz_concrete0_bm-min.png' ) );
                concreteTextures.push( textureLoader.load( '/assets/textures/rsz_concrete0-min.png' ) );
                concreteTextures.push( textureLoader.load( '/assets/textures/rsz_concrete1-min.png' ) );
                concreteTextures.push( textureLoader.load( '/assets/textures/rsz_concrete2-min.jpg' ) );
                bricks_bm.push( textureLoader.load( '/assets/textures/rsz_bricks0_bm-min.png' ) );
                bricks.push( textureLoader.load( '/assets/textures/rsz_bricks0-min.png' ) );
                bricks.push( textureLoader.load( '/assets/textures/rsz_bricks1-min.png' ) );
                bricks.push( textureLoader.load( '/assets/textures/rsz_bricks2-min.png' ) );
                bricks.push( textureLoader.load( '/assets/textures/rsz_bricks3-min.png' ) );
                bricks.push( textureLoader.load( '/assets/textures/rsz_bricks4-min.png' ) );
                bricks.push( textureLoader.load( '/assets/textures/rsz_bricks5-min.jpg' ) );
                roads.push( textureLoader.load( '/assets/textures/roads0-min.png' ) );
                roads.push( textureLoader.load( '/assets/textures/roads1-min.png' ) );
                roads.push( textureLoader.load( '/assets/textures/roads2-min.png' ) );
                road_markings.push( textureLoader.load( '/assets/textures/road_markings/2ln_g.png' ) );
                road_markings.push( textureLoader.load( '/assets/textures/road_markings/2lnh_g.png' ) );
                stones.push( textureLoader.load( '/assets/textures/stones0-min.png' ) );
                woodTextures.push( textureLoader.load( '/assets/textures/rsz_wood1-min.jpg' ) );
                woodTextures.push( textureLoader.load( '/assets/textures/rsz_wood2-min.jpg' ) );
                bananaTextures.push( textureLoader.load( '/assets/textures/rsz_banana-min.jpg' ) );
                marketLogoTextures.push( textureLoader.load( '/assets/textures/milton_market_logo.png' ) );
                toiletSignTx = textureLoader.load( '/assets/textures/toilet_sign.jpg' );
                highlightTextures.push( new THREE.Texture( ) );

                waterNormals = textureLoader.load( '/assets/textures/rsz_bricks4-min.png' );

                $(".loading").html("Loading textures");
                manager.onLoad = function() {
                    // all textures are loaded
                        $(".loading-msg .loading").html("Initializing environment");
                        $(".loading-msg").addClass("initialising");
                        loading_text.innerHTML = '100%';
                        $(".loading-progress").css("width", 100+"%");

                        setTimeout(function(){


                            new OBJLoader( )
                                .setPath( '/assets/models/' )
                                .load( 'banana.obj', function ( obj ) {
                                    var banana_mtl = new THREE.MeshLambertMaterial({map: bananaTextures[0]});
                                    obj.traverse( function ( child ) {

                                        if ( child instanceof THREE.Mesh ) {

                                            child.castShadow = true;
                                            child.receiveShadow = true;
                                            child.material = banana_mtl;

                                        }

                                    } );

                                    banana_obj = obj;

                                    var loader = new THREE.FontLoader();

                                    loader.load( '/assets/node_modules/three/examples/fonts/helvetiker_regular.typeface.json', function ( font ) {

                                        defaultFont = font;

                                        console.log(saveFile);

                                        $.ajax({
                                            url: "/load-game-data",
                                            type: 'post',
                                            data: { id:saveFile.gameId },
                                            dataType: 'json'
                                        }).done(function(data) {

                                            masterProducts = data.products;
                                            masterPositions = data.locs;
                                            masterClients = data.clients;

                                            saveFile.stats.salesBalance = 0;

                                            lastSave = parseInt(saveFile.stats.gameTime)+1;

                                            loading_msg.classList.add("hide");
                                            setTimeout(function(){ loading_msg.remove() }, 600);

                                            //--------------------------------------------------------------------------------------------
                                            //Begin 3d environment
                                            //--------------------------------------------------------------------------------------------


                                            // console.log(lastSave);

                                            init();
                                            animate();

                                            //--------------------------------------------------------------------------------------------

                                        });

                                    } );
                                } );
                        }, 600);

                };

            //end preLoadMTL()
            }

			function init() {

				initGraphics();

				initPhysics();

				createObjects();

				initInput();

			}

            //initialise 3d container, scene, camera, lights, shadows, selection marker, stats
			function initGraphics() {

                // 3d environment container

				container = document.getElementById( 'container' );

                // scene

				scene = new THREE.Scene();
				scene.background = new THREE.Color( 0xcce0ff );
				scene.fog = new THREE.Fog( 0xcce0ff, 2.00, circle_radius*2 );

				// camera

                var savedCameraPosition = saveFile.settings.savedCameraPosition;
                var savedCameraTarget = saveFile.settings.savedCameraTarget;
				camera = new THREE.PerspectiveCamera( 60, window.innerWidth / window.innerHeight, 1, circle_radius*2 );
                camera.position.set( parseInt(savedCameraPosition.x), parseInt(savedCameraPosition.y), parseInt(savedCameraPosition.z) );

				// lights

                atmosphericLight = new THREE.HemisphereLight( 0x394552, 0xa39b84, 1 );

				light = new THREE.DirectionalLight( 0xffe19c, 0.7 );
				light.position.set( 100,200,120 );
                // sunCamera = new THREE.PerspectiveCamera( 90, window.innerWidth / window.innerHeight, 1, circle_radius*2 );
				// sunCamera.position.set( 0,0,0 );
                // sunCamera.lookAt(0,0,0);

                // var spotLightHelper = new THREE.DirectionalLightHelper( light );
                // scene.add( spotLightHelper );

                light.tweens = {};

                // shadows

				light.castShadow = true;

				light.shadow.mapSize.width = 5024;
				light.shadow.mapSize.height = 5024;

				var d = 400.0;

				light.shadow.camera.left = - d;
				light.shadow.camera.right = d;
				light.shadow.camera.top = d;
				light.shadow.camera.bottom = - d;

				light.shadow.camera.far =800.0;

				scene.add( light );

                //----------------------------------------------------------------------------------
                // Sunrise: tween light up
                //----------------------------------------------------------------------------------

                var sunrise = new TWEEN.Tween({y:0}).to({y:200}, 12000);
                sunrise.easing(TWEEN.Easing.Quadratic.InOut)
                sunrise.onUpdate(function(obj){
                    // sunCamera.position.y = obj.y;
                    // sunCamera.lookAt(0,0,0);
                    light.position.y = obj.y;
                    // light.quaternion.copy( sunCamera.quaternion );
                });
                sunrise.onComplete(function(){
                    isSundown = false;
                });

                //----------------------------------------------------------------------------------


                //----------------------------------------------------------------------------------
                // Sunrise: tween light intensity up
                //----------------------------------------------------------------------------------

                var sunrise_intensity = new TWEEN.Tween({intensity:0}).to({intensity:0.7}, 12000);
                sunrise_intensity.easing(TWEEN.Easing.Quadratic.InOut);
                sunrise_intensity.onStart(function(){
                    setTimeout(function(){ light.castShadow = true; }, 600);
                });
                sunrise_intensity.onUpdate(function(obj){
                    light.intensity = obj.intensity;
                });

                //----------------------------------------------------------------------------------


                //----------------------------------------------------------------------------------
                // Sunrise: tween atmospheric light up
                //----------------------------------------------------------------------------------

                var sunrise_atmosphere = new TWEEN.Tween({intensity:0.2}).to({intensity:1}, 12000);
                sunrise_atmosphere.easing(TWEEN.Easing.Quadratic.InOut);
                sunrise_atmosphere.onStart(function(){
                    sunrise.start();
                    // sunrise_intensity.delay(6000);
                    sunrise_intensity.start();
                    new TWEEN.Tween( scene.background ).to( { r: 0.8, g: 0.87, b: 1 }, 9000).start();
                    new TWEEN.Tween( scene.fog.color ).to( { r: 0.8, g: 0.87, b: 1 }, 9000).start();
                });
                sunrise_atmosphere.onUpdate(function(obj){
                    atmosphericLight.intensity = obj.intensity;
                });

                light.tweens.sunrise = sunrise_atmosphere;

                //----------------------------------------------------------------------------------


                //----------------------------------------------------------------------------------
                // Sunset: tween light down
                //----------------------------------------------------------------------------------

                var sunset = new TWEEN.Tween({y:200, }).to({y:0}, 12000);
                sunset.easing(TWEEN.Easing.Quadratic.InOut)
                sunset.onUpdate(function(obj){
                    // sunCamera.position.y = obj.y;
                    // sunCamera.lookAt(0,0,0);
                    light.position.y = obj.y;
                    // light.quaternion.copy( sunCamera.quaternion );
                });
                sunset.onComplete(function(){
                    isSundown = true;
                });

                //----------------------------------------------------------------------------------


                //----------------------------------------------------------------------------------
                // Sunset: tween light intensity down
                //----------------------------------------------------------------------------------

                var sunset_intensity = new TWEEN.Tween({intensity:0.7}).to({intensity:0}, 12000);
                sunset_intensity.easing(TWEEN.Easing.Quadratic.InOut);
                sunset_intensity.onUpdate(function(obj){
                    light.intensity = obj.intensity;
                });

                //----------------------------------------------------------------------------------


                //----------------------------------------------------------------------------------
                // Sunset: tween atmospheric light up
                //----------------------------------------------------------------------------------

                var sunset_atmosphere = new TWEEN.Tween({intensity:1}).to({intensity:0.2}, 12000);
                sunset_atmosphere.easing(TWEEN.Easing.Quadratic.InOut);
                sunset_atmosphere.onStart(function(){
                    sunset.start();
                    // sunset_intensity.delay(6000);
                    sunset_intensity.start();
                    new TWEEN.Tween( scene.background ).to( { r: 0, g: 0.12, b: 0.31 }, 9000).start();
                    new TWEEN.Tween( scene.fog.color ).to( { r: 0, g: 0.12, b: 0.31 }, 9000).start();
                });
                sunset_atmosphere.onUpdate(function(obj){
                    atmosphericLight.intensity = obj.intensity;
                });

                light.tweens.sunset = sunset_atmosphere;

                //----------------------------------------------------------------------------------


                // selection marker

                marker = new THREE.Mesh( new THREE.BoxBufferGeometry( 3.00, 3.00, 500.00 ), new THREE.MeshLambertMaterial( { color: 0xffd62b, opacity: 0.8, transparent: true } ));
                marker.rotation.x = Math.PI;
                marker.position.y = -10000.00;
                marker.name = 'marker';
                scene.add( marker );

                // selection marker animation
                var scale = { x:1.0, z:1.0 };
                var target = { x:1.2, z:1.2 };
                var scaleUpTween = new TWEEN.Tween(scale).to(target, 400);
                scaleUpTween.onUpdate(function(){
                    marker.scale.set(scale.x, 1, scale.z);
                });
                scaleUpTween.start();

                var dscale = { x:1.0, z:1.0 };
                var dtarget = { x:1.2, z:1.2 };
                var scaleDownTween = new TWEEN.Tween(dtarget).to(dscale, 400);
                scaleDownTween.onUpdate(function(){
                    marker.scale.set(dtarget.x, 1, dtarget.z);
                });
                scaleUpTween.chain(scaleDownTween);
                scaleDownTween.chain(scaleUpTween);

                // renderer

                renderer = new THREE.WebGLRenderer({ antialias: true });
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.shadowMap.enabled = true;
				container.appendChild( renderer.domElement );

                // orbit controls

                controls = new OrbitControls( camera, renderer.domElement );
                controls.maxPolarAngle = Math.PI * 0.30;
                controls.minPolarAngle = Math.PI * 0.30;
                controls.minDistance = 70;
                controls.maxDistance = 150;
                // controls.autoRotate = true;
                controls.enableDamping = true;
                controls.dampingFactor = 0.15;
                // controls.enablePan = false;
                controls.autoRotateSpeed = 3.5;
                if(savedCameraTarget!==undefined) {
                    controls.target = new THREE.Vector3(parseInt(savedCameraTarget.x), parseInt(savedCameraTarget.y), parseInt(savedCameraTarget.z));
                }


				scene.add( atmosphericLight );

                // performance monitor

                stats = new Stats();
                container.appendChild( stats.dom );

                // hijack stats.dom and move it into the left menu
                $("#container div").addClass("framerate-ticker d-flex w-100");
                //add framerate-ticker class to find it
                $(".left-menu").append($(".framerate-ticker"));
                //append to the left menu
                $(".framerate-ticker").css("position", "relative");
                //change position from fixed
                $(".framerate-ticker canvas").addClass("d-block");
                //prevent canvases from disappearing on click
                $(".framerate-ticker").prepend('<a href="#" class="btn btn-primary rounded-0 btn-block menu-down d-block"><i class="fas fa-angle-down" style="background: transparent;"></i></a>');
                //add close menu button

                window.addEventListener( 'resize', onWindowResize, false );
                //resize 3d environment if the window frame changes shape

			}

			function initPhysics() {

				// Physics configuration

				var collisionConfiguration = new Ammo.btSoftBodyRigidBodyCollisionConfiguration();
				var dispatcher = new Ammo.btCollisionDispatcher( collisionConfiguration );
				var broadphase = new Ammo.btDbvtBroadphase();
				var solver = new Ammo.btSequentialImpulseConstraintSolver();
				var softBodySolver = new Ammo.btDefaultSoftBodySolver();
				physicsWorld = new Ammo.btSoftRigidDynamicsWorld( dispatcher, broadphase, solver, collisionConfiguration, softBodySolver );
				physicsWorld.setGravity( new Ammo.btVector3( 0, gravityConstant, 0 ) );
				physicsWorld.getWorldInfo().set_m_gravity( new Ammo.btVector3( 0, gravityConstant, 0 ) );

				transformAux1 = new Ammo.btTransform();
				softBodyHelpers = new Ammo.btSoftBodyHelpers();

			}

            //------------------------------------------------------------------
            // create all 3d objects and add them to the scene
            //------------------------------------------------------------------

			function createObjects() {

                //------------------------------------------------------------------
                // Utility 3d object creation functions
                //------------------------------------------------------------------


				function mkTri() {
					var geo=new THREE.Geometry();
					geo.vertices.push(
					    new THREE.Vector3(0,0,0),//vertex0
					    new THREE.Vector3(1000,0,1000),//1
					    new THREE.Vector3(0,0,1000),//2
					    );
					geo.faces.push(
						new THREE.Face3(0,1,2),//vertices[3],1,2...
					    );
					var thisplane = new THREE.Mesh( geo, new THREE.MeshLambertMaterial({ side: THREE.DoubleSide }) );
					thisplane.position.y = 3000;
					return thisplane;
				}

                //open, close and update right panel according to stall selected
				function check_stall_selected() {

					var _stall = false;
					stalls_group.children.forEach((stall) => {
                        // if(stall.name=="stall") {
    						if(stall.selected) {
    							_stall = stall;
    							_stall.npcUpcoming = [];
    							npcs.children.forEach((npc) => {
    								if(npc.target_stall==_stall) {
    									_stall.npcUpcoming.push(npc);
    								};
    							});
    						}
                        // }
					});

					if(_stall) {
                        if(_stall.info.status == 'open') {
                            var stall_client = getClient(_stall.client._clid);
                            $(".right-panel .open-stall").removeClass("hide");
                            $(".right-panel .closed-stall").addClass("hide");
    						$(".right-panel .client-name").html(stall_client.MarketClientName);
    						$(".right-panel .contact-name").html(stall_client.ContactFullName);
    						$(".right-panel .category").html(stall_client.MarketCategoryName);
    						$(".right-panel .sales").html("$"+parseFloat(_stall.info.sales).toFixed(2));
    						if(_stall.npcUpcoming.length!=0) {
    							$(".right-panel .sales").append("<br>"+_stall.npcUpcoming.length+" approaching");
    						}
                            // console.log(stall_client);
                            console.log(_stall);
    						$(".right-panel .product-0 .name").html(getProductName(_stall.client._p[0]._pid));
    						$(".right-panel .product-1 .name").html(getProductName(_stall.client._p[1]._pid));
    						$(".right-panel .product-2 .name").html(getProductName(_stall.client._p[2]._pid));
    						$(".right-panel .product-0 .price").html("$"+_fcur(_stall.client._p[0]._pr));
    						$(".right-panel .product-1 .price").html("$"+_fcur(_stall.client._p[1]._pr));
    						$(".right-panel .product-2 .price").html("$"+_fcur(_stall.client._p[2]._pr));
                            $(".right-panel .satisfaction-pr").css("width", _stall.client.sc._cs+'%');
                            $(".right-panel .margin-pr").css("width", _stall.client.sc._pm+'%');
                            $(".right-panel .costs-pr").css("width", _stall.client.sc._rc+'%');
                            $(".right-panel .appeal-pr").css("width", _stall.client.sc._ca+'%');
                        } else {
                            //closed mode
                            $(".right-panel .open-stall").addClass("hide");
                            $(".right-panel .closed-stall").removeClass("hide");


                            $(".new-stall-container").addClass("hide");
                            $(".new-stall-category-btn").removeClass("hide btn-primary").addClass("btn-menu");
                            // $(".right-panel .client-name").html(_stall.info.MarketClientName);
                            // $(".right-panel .contact-name").html(_stall.info.ContactFullName);
                            // $(".right-panel .category").html(_stall.info.MarketCategoryName);
                            // $(".right-panel .attendance-pr").css("width", _stall.info._cas*100+'%');
                            // $(".right-panel .revenue-pr").css("width", _stall.info._cass*100+'%');
                            // $(".right-panel .rain-pr").css("width", _stall.info._crd*100+'%');
                            // $(".right-panel .score-pr").css("width", _stall.info._cst*100+'%');
                            // $(".right-panel .contact-name").html(_stall.info.ContactFullName);
                            $(".right-panel .locname").html(_stall.locName);
                        }
						// $(".right-panel .card-text small").html(start[Math.floor(Math.random()*start.length)]+ob[Math.floor(Math.random()*ob.length)]+sell[Math.floor(Math.random()*sell.length)]+prod[Math.floor(Math.random()*prod.length)]);
						$(".right-panel").removeClass("hide");
					} else {
						$(".right-panel").addClass("hide");
					}

				}

                //------------------------------------------------------------------
                // chalkboard base model
                //------------------------------------------------------------------

                chalkboardBase = new THREE.Group();
                var chalkboardBase_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(4.0, 1.8, 2.0),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[1]
                            })
                        })
                    );
                    chalkboardBase_obj.castShadow = true;
                    chalkboardBase_obj.receiveShadow = false;
                    chalkboardBase_obj.position.set(0, 0.9, 0);
                chalkboardBase.add( chalkboardBase_obj );
                function mkChalkboardBase() {
                    var p = chalkboardBase.clone();
                    return p;
                }

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // ground
                //------------------------------------------------------------------

                pos.set( 0, -2.40, 0 );
                quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 180 * Math.PI / 360 ); //flat shape horizontal
                var ground0 = mkShape(0, pos, quat, setMtls(concreteTextures[2], 0.009, 0.015), [[-.52,-219.79],[-39.89,-215.97],[-48.90,-163.08],[-84.67,-127.92],[-140.74,-109.86],[-166.38,-70.42],[-153.83,12.62],[-153.41,85.65],[-73.25,109.81],[61.64,143.93],[161.28,138.06],[192.61,4.45],[212.55,-129.01],[67.52,-167.46],[26.20,-203.47],[9.46,-204.24]]);
                pos.set( 0, -2.10, 0 );
                var grass1 = mkShape(0, pos, quat, setMtls(grasses[0], 0.13, 0.13),[[60,14],[21,14],[21,-50],[60,-50]]);
                var grass1_underlay = mkShape(0, new THREE.Vector3(0, -2.15, 0), quat, setBmMtls(setTexture({ texture: bricks[0], reptx:0.3, repty:0.3, offsetx:0.1, offsety:0.1 }), setTexture({ texture: bricks_bm[0], reptx:0.3, repty:0.3, offsetx:0.1, offsety:0.1 }), 0x222222, 2, 1 ),[[65,25],[65,-60],[10,-60],[10,25]]);
				var grass2 = mkShape(0, pos, quat, setMtls(grasses[0], 0.13, 0.13),[[-10.19,-128.47],[-33.13,-108.40],[-33.80,-96.91],[-124.48,-95.74],[-128.08,-93.82],[-130.12,-91.00],[-126.20,65.12],[-113.29,76.43],[-20.01,78.74],[-18.96,117.10],[-143.80,81.35],[-141.44,7.77],[-150.23,-65.68],[-135.48,-104.56],[-78.65,-120.13],[-32.67,-143.55]]);
				var grass3 = mkShape(0, pos, quat, setMtls(grasses[0], 0.13, 0.13),[[23.51,60.28],[20.70,120.27],[72.90,132.67],[147.08,120.53],[176.04,-61.17],[145.47,-132.65],[30.70,-154.79],[6.00,-152.73],[10.72,-81.32],[87.46,-81.57],[84.32,58.34],[24.32,59.49]]);
                pos.set( 0, -2.20, 0 );
				var concrete1 = mkShape(0, pos, quat, setMtls(roads[2], 0.2, 0.2),[[-131.10,-90.89],[-127.70,-67.75],[-127.04,64.00],[-114.32,77.72],[-17.49,77.02],[-9.36,-94.22],[-126.69,-95.99]]);
				var concrete2 = mkShape(0, pos, quat, setMtls(roads[2], 0.2, 0.2),[[-24.72,76.54],[-20.93,115.84],[25.57,118.94],[22.28,61.05],[86.63,59.99],[84.72,-86.33],[10.96,-80.56],[13.21,-108.36],[-33.97,-111.48],[-35.57,-88.82]]);
                // quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 360 * Math.PI / 360 ); //flat shape vertical

                pos.set( 0, -2.00, 0 );
                quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 360 * Math.PI / 360 ); //flat shape vertical
                var mainroad = createParalellepiped( 12.00, 0.15, circle_radius*6, 0, new THREE.Vector3( 0, -1.96, 0 ), quat, setMtls(roads[0], 2, 150) );
                var mainroad_path = createParalellepiped( 16.00, 0.15, circle_radius*6, 0, new THREE.Vector3( 0, -1.98, 0 ), quat, setMtls(concreteTextures[1], 4, 500) );
                var mainroad_concrete = createParalellepiped( 24.00, 0.15, circle_radius*6, 0, new THREE.Vector3( 0, -2.00, 0 ), quat, setMtls(concreteTextures[0], 4, 200) );
                pos.set( 0, -1.9, 0 );
                var main_road_overlay_tx = setTexture({ texture: road_markings[0], reptx:1, repty:150, offsetx:0, offsety:0 });
                var main_road_overlay_mtls = new THREE.MeshLambertMaterial( { map: main_road_overlay_tx, opacity: 0.8, transparent: true } );
                var mainroad_overlay = createParalellepiped( 12.00, 0.30, circle_radius*6, 0, pos, quat, main_road_overlay_mtls );
                pos.set( 0, -2.50, 0 );
                // var floordrop = createParalellepiped( circle_radius*6, 0.30, circle_radius*6, 0, pos, quat, setMtls(roads[1], 100,100) );
                // floordrop.rotation.y = Math.PI/3;
                pos.set( 0, 0, 0 );
                var levelcheckgeo = new THREE.BoxBufferGeometry(circle_radius*6, 0.30, circle_radius*6);
				var levelcheck = new THREE.Mesh(levelcheckgeo, new THREE.MeshLambertMaterial( { color: 0xffffff, opacity: 0, transparent: true } ) );
				levelcheck.receiveShadow = false;
				levelcheck.castShadow = false;

				floorGroup = new THREE.Group();
                floorGroup.add( ground0 );
                floorGroup.add( grass1 );
                floorGroup.add( grass1_underlay );
                floorGroup.add( grass2 );
                floorGroup.add( grass3 );
                floorGroup.add( concrete1 );
                floorGroup.add( concrete2 );
                floorGroup.add( mainroad );
                floorGroup.add( mainroad_overlay );
                floorGroup.add( mainroad_path );
				floorGroup.add( mainroad_concrete );
                // floorGroup.add( floordrop );
				floorGroup.add( levelcheck );
                floorGroup.name = 'floor';

                scene.add( floorGroup );



                //------------------------------------------------------------------
                // pallet model
                //------------------------------------------------------------------

                pallets = new THREE.Group();
                pallet = new THREE.Group();
                var pallet_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(3.6, 0.05, 3.6),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[1]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(0, 0.25, 0);
                pallet.add( pallet_obj );
                var pallet_obj = //edge support panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(3.6, 0.5, 0.15),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[0]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(0, 0, -1.7);
                pallet.add( pallet_obj );
                var pallet_obj = //edge support panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(3.6, 0.5, 0.15),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[0]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(0, 0, 1.7);
                pallet.add( pallet_obj );
                var pallet_obj = //middle support panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(3.6, 0.5, 0.15),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[0]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(0, 0, 0);
                pallet.add( pallet_obj );
                var pallet_obj = //bottom panels
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.6, 0.05, 3.6),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[1]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(-1.5, -0.30, 0);
                pallet.add( pallet_obj );
                var pallet_obj = //bottom panels
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.6, 0.05, 3.6),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[1]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(1.5, -0.30, 0);
                pallet.add( pallet_obj );
                var pallet_obj = //bottom panels
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.9, 0.05, 3.6),
                        setMtl({
                            map:setTx({
                                texture:woodTextures[1]
                            })
                        })
                    );
                    pallet_obj.castShadow = true;
                    pallet_obj.receiveShadow = false;
                    pallet_obj.position.set(0, -0.30, 0);
                pallet.add( pallet_obj );
                function mkPallet( pos, quat ) {
                    var p = pallet.clone();
                    var sx = 1.8;
                    var sy = 0.3;
                    var sz = 1.8;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );

                    var palletBody = createRigidBody( p, shape, 1, pos, quat );
                    palletBody.setFriction( 1 );
                    pallets.add( p );
                }

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // box chair model
                //------------------------------------------------------------------

				var box_chair_mesh = new THREE.Mesh(new THREE.BoxBufferGeometry(1, 1, 1), setMtls(woodTextures[1], 0.4, 0.8) );
				box_chair_mesh.position.y = 0;
				box_chair_mesh.receiveShadow = false;
				box_chair_mesh.castShadow = true;
				var chairs = new THREE.Group();
				var chair = new THREE.Group();
				chair.name = 'chair';
				chair.add( box_chair_mesh );
				chair.scale.set(1.50,1.50,1.50);
				function mkNewChair(pos, quat) {
					var newChair = chair.clone();
                    var sx = 0.75;
                    var sy = 0.75;
                    var sz = 0.75;
    				var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
    				shape.setMargin( margin );
                    var chairBody = createRigidBody( newChair, shape, 1, pos, quat );
                    chairBody.setFriction( 1 );
					chairs.add( newChair );
				}

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // table model
                //------------------------------------------------------------------

				var table_base_geo = new THREE.CylinderBufferGeometry(6, 6, 0.5, 8);
				var table_center_geo = new THREE.CylinderBufferGeometry(4, 4, 10, 10);
				var table_top_geo = new THREE.CylinderBufferGeometry(10, 10, 1, 8);
				var table_base_mesh = new THREE.Mesh(table_base_geo, setMtls(woodTextures[1], 1, 1) );
				var table_center_mesh = new THREE.Mesh(table_center_geo, setMtls(woodTextures[1], 1, 1) );
				var table_top_mesh = new THREE.Mesh(table_top_geo, setMtls(woodTextures[1], 0.5, 0.5) );
				table_base_mesh.position.y = -5.25;
				table_center_mesh.position.y = 0.5;
				table_top_mesh.position.y = 6;
				table_base_mesh.receiveShadow = true;
				table_base_mesh.castShadow = true;
				table_center_mesh.receiveShadow = true;
				table_center_mesh.castShadow = true;
				table_top_mesh.receiveShadow = true;
				table_top_mesh.castShadow = true;
				var tables = new THREE.Group();
				var table = new THREE.Group();
				table.name = 'table';
				table.add( table_base_mesh );
				table.add( table_center_mesh );
				table.add( table_top_mesh );
				table.scale.set(0.20,0.20,0.20);
				function mkNewTable(pos, quat) {
					var newTable = table.clone();
                    var sx = 1.00;
                    var sy = 1.00;
                    var sz = 1.00;
    				var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
    				shape.setMargin( margin );

                    var tableBody = createRigidBody( newTable, shape, 1, pos, quat );
                    tableBody.setFriction( 1 );
					tables.add( newTable );
				}

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // rubbish bin model
                //------------------------------------------------------------------

				var bin_base_geo = new THREE.CylinderBufferGeometry(5.4, 4.5, 10.0, 8);
				var bin_lid_geo = new THREE.CylinderBufferGeometry(2.6, 5.6, 1.7, 8);
				var bin_base_mesh = new THREE.Mesh(bin_base_geo, new THREE.MeshLambertMaterial({ color: 0x00620e }) );
				var bin_lid_mesh = new THREE.Mesh(bin_lid_geo, new THREE.MeshLambertMaterial({ color: 0x000000 }) );
				bin_base_mesh.position.y = 0;
				bin_lid_mesh.position.y = 5.85;
				bin_base_mesh.receiveShadow = true;
				bin_base_mesh.castShadow = true;
				bin_lid_mesh.receiveShadow = false;
				bin_lid_mesh.castShadow = true;
				var bins = new THREE.Group();
				var bin = new THREE.Group();
				bin.name = 'bin';
				bin.add( bin_base_mesh );
				bin.add( bin_lid_mesh );
				bin.scale.set(0.15,0.20,0.15);
				var _ys = 0;
				function mkNewBin(pos, quat) {
					var newBin = bin.clone();
                    var sx = 0.75;
                    var sy = 0.90;
                    var sz = 0.75;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );
                    var binBody = createRigidBody( newBin, shape, 2, pos, quat );
                    binBody.setFriction( 1 );
					bins.add( newBin );
				}

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // light pole model
                //------------------------------------------------------------------

                var lightpole_light = new THREE.PointLight( 0xffffff, 0, 100, 2 );
                lightpole_light.position.set( 0, 30, 0 );
                lightpole_light.name = 'light';
                var lightpole_base_geo = new THREE.RingBufferGeometry(
                    0.15, 1.6,
                    9, 2,
                    Math.PI * 0.25, Math.PI * 1.5);
                var lightpole_base_mesh = new THREE.Mesh(lightpole_base_geo, setMtls(concreteTextures[1], 0.4, 0.4) );
                var lightpole_light_frame_geo = new THREE.SphereBufferGeometry(
                    1,
                    10, 3,
                    Math.PI * 1.36, Math.PI * 2.00,
                    Math.PI * 0.05, Math.PI * 0.15);
                var lightpole_light_frame_mesh = new THREE.Mesh(lightpole_light_frame_geo, new THREE.MeshLambertMaterial({ color: 0xeaeaea }) );
				var lightpole_frame_geo = new THREE.CylinderBufferGeometry(0.1, 0.1, 20, 10);
				var lightpole_frame_mesh = new THREE.Mesh(lightpole_frame_geo, new THREE.MeshLambertMaterial({ color: 0xeaeaea }) );
                var lightpole = new THREE.Group();
                lightpole_base_mesh.rotation.x = Math.PI/2;
                lightpole_frame_mesh.rotation.x = Math.PI*2;
                // lightpole_light_frame_mesh
                lightpole_base_mesh.position.y = 0.1;
                lightpole_frame_mesh.position.y = 10;
                lightpole_light_frame_mesh.position.y = 19.2;
                lightpole_base_mesh.receiveShadow = true;
                lightpole_base_mesh.castShadow = true;
                lightpole_frame_mesh.receiveShadow = true;
                lightpole_frame_mesh.castShadow = true;
                lightpole_light_frame_mesh.receiveShadow = true;
                lightpole_light_frame_mesh.castShadow = true;
                lightpole_base_mesh.needsUpdate = true;
                lightpole_base_mesh.material.needsUpdate = true;
                lightpole_frame_mesh.needsUpdate = true;
                lightpole_frame_mesh.material.needsUpdate = true;
                lightpole_light_frame_mesh.needsUpdate = true;
                lightpole_light_frame_mesh.material.needsUpdate = true;
                lightpole.add( lightpole_base_mesh );
                lightpole.add( lightpole_frame_mesh );
                lightpole.add( lightpole_light_frame_mesh );
                lightpole.add( lightpole_light );
                lightpole.name = 'lightpole';
                function mkLightPole(pos, quat) {
                    var lp = lightpole.clone();
                    lightpoles_group.add( lp );
                    var sx = 0.05;
                    var sy = 20;
                    var sz = 0.05;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );
                    createRigidBody( lp, shape, 0, pos, quat );

                    lp.turnOn = new TWEEN.Tween({i:0}).to({i:0.9}, 500);
                    lp.turnOn.easing(TWEEN.Easing.Quadratic.InOut);
                    lp.turnOn.onUpdate(function(obj){
                        lp.getObjectByName('light').intensity = obj.i;
                    });

                    lp.turnOff = new TWEEN.Tween({i:0.9}).to({i:0}, 500);
                    lp.turnOff.easing(TWEEN.Easing.Quadratic.InOut);
                    lp.turnOff.onUpdate(function(obj){
                        lp.getObjectByName('light').intensity = obj.i;
                    });
                }

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // add objects to scene
                //------------------------------------------------------------------

                // bins [ x, z ]
				var bs = [[17.82,33.32],[-35.36,31.37],[69.64,42.87],[-101.83,57.93],[-32.60,66.88],[-19.86,-58.34],[71.99,-79.64],[-95.79,-70.82],[-35.40,-87.17]];
				bs.forEach((b) => {
                    pos.set(b[0], _ys, b[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 0 * Math.PI / 360 ); //flat shape horizontal
					mkNewBin(pos, quat);
				});

                // tables [ x, z ]
				var ts = [[-55.84,-60.72],[-72.55,-59.97],[-83.34,-51.11],[-69.18,-50.37],[-43.58,-49.06],[-31.54,-58.89],[-19.32,-38.56],[-47.62,-39.50],[-74.65,-39.22]];
				ts.forEach((t) => {
                    pos.set(t[0], 0, t[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 0 * Math.PI / 360 ); //flat shape horizontal
					mkNewTable(pos, quat);
				});

                // box chairs [ x, z ]
				var cs = [[-85.28,-49.10],[-84.81,-53.15],[-78.68,-53.14],[-74.61,-57.45],[-75.41,-61.60],[-68.72,-61.51],[-69.11,-55.77],[-71.54,-48.95],[-77.42,-40.13],[-71.10,-40.14],[-81.05,-47.90],[-58.80,-58.36],[-57.10,-63.66],[-46.35,-51.12],[-42.42,-46.42],[-22.24,-41.09],[-31.29,-56.43],[-31.27,-61.15],[-35.41,-57.12],[-51.34,-38.91],[-47.85,45.58],[-48.01,4.265],[-44.86,38.00]];
				var cs_2 = [[-123.78,-91.11],[-127.33,-84.84],[61.71,16.88],[5665,16.37],[43.30,18.48],[19.43,-3.59],[63.16,-34.31],[28.42,-51.12],[17.39,-76.57],[24.13,-80.66],[58.96,-80.57],[64.23,-80.39],[-21.72,-106.88],[81.27,53.42]];
                var cs_3 = [[44,-21],[52,-29],[56,-34],[56,-23],[39,-17],[39,-14],[42,-13],[44,-18],[48,-15],[47,-12],[48,-21],[54,-18],[45,-19],[49,-42],[57,-43]];
                var cs_4 = [[95,-99],[110,-97],[108,-108],[89,-106],[89,-87],[89,-118]];
				cs.forEach((c) => {
                    pos.set(c[0], 0, c[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), rndNeg(180) * Math.PI / 360 ); //flat shape horizontal
					mkNewChair(pos, quat);
				});
                cs_2.forEach((c) => {
                    pos.set(c[0], 0, c[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), rndNeg(180) * Math.PI / 360 ); //flat shape horizontal
					mkNewChair(pos, quat);
				});
                cs_3.forEach((c) => {
                    pos.set(c[0], 0, c[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), rndNeg(180) * Math.PI / 360 ); //flat shape horizontal
					mkNewChair(pos, quat);
				});
                cs_4.forEach((c) => {
                    pos.set(c[0], 0, c[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), rndNeg(180) * Math.PI / 360 ); //flat shape horizontal
					mkNewChair(pos, quat);
				});

                // light poles [ x, z ]
                var lps = [[-113,59],[-24,58],[70,41],[67,-76],[-16,-94],[18,-17]];
                lps.forEach((c) => {
                    pos.set(c[0], -2, c[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 0 * Math.PI / 360 ); //flat shape horizontal
					mkLightPole(pos, quat);
				});
                lps = [[-38,-50],[-87,-52]];
                lps.forEach((c) => {
                    pos.set(c[0], -1.8, c[1]);
                    quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 0 * Math.PI / 360 ); //flat shape horizontal
					mkLightPole(pos, quat);
				});

                // pallets [ x, z, y, stack height ]
                var ps = [[-46,43,-1,6],[-31,-39, -1, 2],[21,-32,-1,2],[53,-42,-1,2],[55,9,-1,2],[-70,19,12,3],[-96,59,-1,4],[-134,37,-1,1],[-143,-28,-1,4],[-135,-51,-1,2],[39,-26,-1,5],[41,-38,-1,5],[35,-32,-1,5]];
                var ps2 = [[65,71,-1,2],[65,77,-1,2],[76,77,-1,2],[76,71,-1,2],[26,-93,-1,2],[19,-91,-1,2],[25,-104,-1,2],[21,-105,-1,2]];
                ps.forEach((p) => {
                    var _angle = rndNeg(180);
                    for(var g=0; g<p[3]; g++) {
                        pos.set(p[0], (p[2]+(g*0.6)), p[1]);
                        quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), (_angle+rndNeg(15)) * Math.PI / 360 ); //flat shape horizontal
    					mkPallet(pos, quat);
                    }
				});
                ps2.forEach((p) => {
                    var _angle = rndNeg(180);
                    for(var g=0; g<p[3]; g++) {
                        pos.set(p[0], (p[2]+(g*0.6)), p[1]);
                        quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), (_angle+rndNeg(15)) * Math.PI / 360 ); //flat shape horizontal
    					mkPallet(pos, quat);
                    }
				});

                // add groups to scene
                scene.add( chairs );
                scene.add( tables );
                scene.add( bins );
                scene.add( lightpoles_group );
                scene.add( pallets );

                //------------------------------------------------------------------


                //------------------------------------------------------------------
                // stalls
                //------------------------------------------------------------------

                stalls_group.name = 'stalls_group';
                $(masterPositions).each(function() {

                    // make stall
                    var _stall = mkStall((this.mapLocX-1500)*0.3, 0, (this.mapLocY-1350)*0.3, this.hasAwning);

                    // check if stall is built
                    var selected_stall = false;
                    if(savedStalls) {
                        savedStalls.forEach((st) => {
                            if(st.locId==this.mapLocId) {
                                selected_stall = st;
                            }
                        });
                    }

                    // set stall data
                    _stall.loc = this;
                    _stall.info = {};
                    _stall.locId = this.mapLocId;
                    _stall.locName = this.mapLocName;
                    _stall.hovering = false;
                    _stall.selected = false;
                    _stall.LocRotate = this.LocRotate;
                    if(selected_stall) {
                        //get client
                        localClients.forEach((cl) => {
                            if(cl._clid==selected_stall.clientId) {
                                _stall.client = cl;
                            }
                        });
                        _stall.info.sales = selected_stall.sales;
                        _stall.info.cust = selected_stall.cust;
                        _stall.info.time = selected_stall.time;
                        selected_stall.client = _stall.client;
                    } else {
                        _stall.client = false;
                        _stall.info.sales = 0;
                        _stall.info.time = 0;
                        _stall.info.cust = 0;
                    }

                    // create physics boundaries
                    var sx = 4;
                    var sy = 4.0;
                    var sz = 4;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );
                    pos.set( (this.mapLocX-1500)/3.33, 2, (this.mapLocY-1350)/3.33 );
                    quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), (Math.PI/2)*Math.floor((-this.LocRotate)/90) ); //flat shape horizontal
                    var _stallbody = createRigidBody( _stall, shape, 0, pos, quat );
                    stalls_group.add( _stall );
                    _stall.scale.set(0.9,0.9,0.9);
                    _stallbody.setFriction( 1 );

                    // stall animations
                    // animations are chained together so that the frame and furniture follow eachother with some lag
                    var stallFurnitureMoveUp;
                    var stallFrameMoveUp;
                    var stallFurnitureMoveDown;
                    var stallFrameMoveDown;

                    // tween furniture up
                    stallFurnitureMoveUp = new TWEEN.Tween({y:-5}).to({y:0}, 2000);
                    stallFurnitureMoveUp.easing(TWEEN.Easing.Elastic.Out)
                    stallFurnitureMoveUp.onUpdate(function(obj){
                        _stall.getObjectByName("stallFrame").getObjectByName("stallFurniture").position.y = obj.y;
                    });
                    _stall.slideFurnitureUp = stallFurnitureMoveUp;

                    // tween stall frame down
                    stallFrameMoveDown = new TWEEN.Tween({y:0}).to({y:-10}, 3000);
                    stallFrameMoveDown.easing(TWEEN.Easing.Elastic.In)
                    stallFrameMoveDown.onUpdate(function(obj){
                        _stall.getObjectByName("stallFrame").position.y = obj.y;
                    });
                    _stall.slideFrameDown = stallFrameMoveDown;

                    // tween stall frame up
                    stallFrameMoveUp = new TWEEN.Tween({y:-10}).to({y:0}, 3000);
                    stallFrameMoveUp.easing(TWEEN.Easing.Elastic.Out)
                    stallFrameMoveUp.onUpdate(function(obj){
                        _stall.getObjectByName("stallFrame").position.y = obj.y;
                    });
                    _stall.slideFrameUp = stallFrameMoveUp;

                    // tween stall furniture down
                    stallFurnitureMoveDown = new TWEEN.Tween({y:0}).to({y:-5}, 2000);
                    stallFurnitureMoveDown.easing(TWEEN.Easing.Elastic.In)
                    stallFurnitureMoveDown.onUpdate(function(obj){
                        _stall.getObjectByName("stallFrame").getObjectByName("stallFurniture").position.y = obj.y;
                    });
                    _stall.slideFurnitureDown = stallFurnitureMoveDown;

                    // change stall settings when animation runs ( to open )
                    stallFrameMoveUp.onStart(function(obj){
                        _stall.getObjectByName('stallSelectFrame').scale.set(1,1,1);
                        _stall.getObjectByName('stallSelectFrame').position.y = 0;
                        _stall.getObjectByName("stallFrame").visible = true;
                        _stall.nextMovement = stallFurnitureMoveDown;
                        _stall.info.status = 'open';
                        check_stall_selected();
                    });

                    // change stall settings when animation runs ( to closed )
                    stallFurnitureMoveDown.onStart(function(obj){
                        _stall.getObjectByName('stallSelectFrame').scale.set(1,0.3,1);
                        _stall.getObjectByName('stallSelectFrame').position.y = -3;
                        _stall.nextMovement = stallFrameMoveUp;
                        _stall.info.status = 'closed';
                        check_stall_selected();
                    });

                    // _stall.stallFrameMoveUp.start() will now handle all other data changes on state change

                    stallFrameMoveUp.onComplete(function(){
                        _stall.slideFurnitureUp.start();
                    });

                    stallFurnitureMoveDown.onComplete(function(){
                        _stall.slideFrameDown.start();
                    });

                    stallFrameMoveDown.onComplete(function(){
                        _stall.getObjectByName("stallFrame").visible = false;
                    });

                    //set starting positions of internal stall objects
                    if(!selected_stall) {
                        _stall.info.status = 'closed';
                        _stall.getObjectByName('stallSelectFrame').scale.set(1,0.3,1);
                        _stall.getObjectByName('stallSelectFrame').position.y = -3;
                        _stall.getObjectByName("stallFrame").position.y = -10;
                        _stall.getObjectByName("stallFrame").getObjectByName("stallFurniture").position.y = -5;
                        _stall.getObjectByName("stallFrame").visible = false;
                        _stall.nextMovement = stallFrameMoveUp;
                    } else {
                        _stall.info.status = 'open';
                        _stall.getObjectByName('stallSelectFrame').scale.set(1,1,1);
                        _stall.getObjectByName('stallSelectFrame').position.y = 0;
                        _stall.getObjectByName("stallFrame").position.y = 0;
                        _stall.getObjectByName("stallFrame").getObjectByName("stallFurniture").position.y = 0;
                        _stall.getObjectByName("stallFrame").visible = true;
                        new TWEEN.Tween(_stall.getObjectByName("stallFloor").material.color).to({r:0.41, g:0.48, b:0.59},9000).start();
                        _stall.nextMovement = stallFurnitureMoveDown;
                    }

                });

                scene.add( stalls_group );


                //--------------------------------------------------------------------------------------------
                // windmill model
                //--------------------------------------------------------------------------------------------

                windmill = new THREE.Group();
                windmillBlades = new THREE.Group();
                windmillVertical = new THREE.Group();
                windmillVertical.name = 'windmillVertical';
                var maxHeight = 30;
                var windmill_obj = //Base
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(11, 2.5, 11),
                        setMtls(woodTextures[1], 1, 1)
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    windmill_obj.position.set(0, -14, 0);
                windmill.add( windmill_obj );
                var windmill_obj = //Vertical pole
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.1, maxHeight, 0.1),
                        setMtls(woodTextures[0], 1, 1)
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    windmill_obj.position.set(0, 4, 0);
                windmillVertical.add( windmill_obj );
                var windmill_obj = //Air direction catcher? Thing?
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.1, 1, 2),
                        setMtls(woodTextures[1], 1, 0.7)
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    windmill_obj.position.set(0, 19, -4);
                windmillVertical.add( windmill_obj );
                var windmill_obj = //Axel for blades
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.1, 8, 0.1),
                        setMtl({
                            color: 0x2c2c2c
                        })
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    windmill_obj.position.set(0, 0, 0);
                    windmill_obj.rotation.x = THREE.Math.degToRad( 90 );
                windmillBlades.add( windmill_obj );
                var windmill_obj = //Axel center shape
                    new THREE.Mesh(
                        new THREE.CylinderBufferGeometry(0.5, 1, 2, 5),
                        setMtls(woodTextures[0], 3, 1)
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    windmill_obj.position.set(0, 0, 3.5);
                    windmill_obj.rotation.x = THREE.Math.degToRad( 90 );
                windmillBlades.add( windmill_obj );
                var windmill_obj = //Axel center shape
                    new THREE.Mesh(
                        new THREE.CylinderBufferGeometry(0.6, 1.2, 1.8, 3),
                        setMtls(woodTextures[1], 1, 1)
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    windmill_obj.position.set(0, 0, 3.5);
                    windmill_obj.rotation.x = THREE.Math.degToRad( 90 );
                windmillBlades.add( windmill_obj );
                function mkWindmillBlade(u) {
                    var windmill_obj = //blade
                        new THREE.Mesh(
                            new THREE.SphereBufferGeometry(
                                10,
                                9, 3,
                                Math.PI * u, Math.PI * 0.13,
                                Math.PI * 0.83, Math.PI * 0.12),
                                setMtls(woodTextures[1], 0.2, 0.4)

                        );
                        windmill_obj.castShadow = true;
                        windmill_obj.receiveShadow = false;
                        windmill_obj.position.set(0, 0, 13);
                        windmill_obj.rotation.x = THREE.Math.degToRad( 90 );
                    return windmill_obj
                }
                windmillBlades.add( mkWindmillBlade(0.00) );
                windmillBlades.add( mkWindmillBlade(0.15) );
                windmillBlades.add( mkWindmillBlade(0.30) );
                windmillBlades.add( mkWindmillBlade(0.45) );
                windmillBlades.add( mkWindmillBlade(0.60) );
                windmillBlades.add( mkWindmillBlade(0.75) );
                windmillBlades.add( mkWindmillBlade(0.90) );
                windmillBlades.add( mkWindmillBlade(1.05) );
                windmillBlades.add( mkWindmillBlade(1.20) );
                windmillBlades.add( mkWindmillBlade(1.35) );
                windmillBlades.add( mkWindmillBlade(1.50) );
                windmillBlades.add( mkWindmillBlade(1.65) );
                windmillBlades.add( mkWindmillBlade(1.80) );
                windmillBlades.add( mkWindmillBlade(1.95) );
                windmillBlades.name = 'windmillBlades';
                windmillBlades.position.y = 19;
                windmillVertical.add( windmillBlades );
                windmill.add( windmillVertical );
                var windmill_obj = //windmill pole
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.1, 30, 0.1),
                        setMtls(woodTextures[0], 1, 1)
                    );
                    windmill_obj.castShadow = true;
                    windmill_obj.receiveShadow = false;
                    var windmill_poles = [];
                    for(var i=0; i<22; i++) {
                        windmill_poles.push( windmill_obj.clone() );
                    }

                    var lgPoleThickness = 3;
                    var medPoleThickness = 2;
                    var intersectPoleThickness = 1;

                    windmill_poles[0].position.set(3, 0, 3);
                    windmill_poles[0].scale.set(lgPoleThickness, 1, lgPoleThickness);
                    windmill_poles[0].quaternion.setFromAxisAngle( new THREE.Vector3(-1, 0, 1), 12*Math.PI/360);
                    windmill_poles[1].position.set(-3, 0, 3);
                    windmill_poles[1].scale.set(lgPoleThickness, 1, lgPoleThickness);
                    windmill_poles[1].quaternion.setFromAxisAngle( new THREE.Vector3(-1, 0, -1), 12*Math.PI/360);
                    windmill_poles[2].position.set(3, 0, -3);
                    windmill_poles[2].scale.set(lgPoleThickness, 1, lgPoleThickness);
                    windmill_poles[2].quaternion.setFromAxisAngle( new THREE.Vector3(1, 0, 1), 12*Math.PI/360);
                    windmill_poles[3].position.set(-3, 0, -3);
                    windmill_poles[3].scale.set(lgPoleThickness, 1, lgPoleThickness);
                    windmill_poles[3].quaternion.setFromAxisAngle( new THREE.Vector3(1, 0, -1), 12*Math.PI/360);

                    windmill_poles[4].position.set(0, maxHeight/2, 1.5);
                    windmill_poles[4].scale.set(medPoleThickness, 0.1, medPoleThickness);
                    windmill_poles[4].rotation.y = THREE.Math.degToRad( 0 );
                    windmill_poles[4].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[5].position.set(1.5, maxHeight/2, 0);
                    windmill_poles[5].scale.set(medPoleThickness, 0.1, medPoleThickness);
                    windmill_poles[5].rotation.y = THREE.Math.degToRad( 90 );
                    windmill_poles[5].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[6].position.set(-1.5, maxHeight/2, 0);
                    windmill_poles[6].scale.set(medPoleThickness, 0.1, medPoleThickness);
                    windmill_poles[6].rotation.y = THREE.Math.degToRad( 90 );
                    windmill_poles[6].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[7].position.set(0, maxHeight/2, -1.5);
                    windmill_poles[7].scale.set(medPoleThickness, 0.1, medPoleThickness);
                    windmill_poles[7].rotation.y = THREE.Math.degToRad( 0 );
                    windmill_poles[7].rotation.z = THREE.Math.degToRad( 90 );

                    windmill_poles[8].position.set(0, maxHeight/3, 2);
                    windmill_poles[8].scale.set(medPoleThickness, 0.15, medPoleThickness);
                    windmill_poles[8].rotation.y = THREE.Math.degToRad( 0 );
                    windmill_poles[8].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[9].position.set(2, maxHeight/3, 0);
                    windmill_poles[9].scale.set(medPoleThickness, 0.15, medPoleThickness);
                    windmill_poles[9].rotation.y = THREE.Math.degToRad( 90 );
                    windmill_poles[9].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[10].position.set(-2, maxHeight/3, 0);
                    windmill_poles[10].scale.set(medPoleThickness, 0.15, medPoleThickness);
                    windmill_poles[10].rotation.y = THREE.Math.degToRad( 90 );
                    windmill_poles[10].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[11].position.set(0, maxHeight/3, -2);
                    windmill_poles[11].scale.set(medPoleThickness, 0.15, medPoleThickness);
                    windmill_poles[11].rotation.y = THREE.Math.degToRad( 0 );
                    windmill_poles[11].rotation.z = THREE.Math.degToRad( 90 );

                    windmill_poles[12].position.set(0, -maxHeight/3, 4);
                    windmill_poles[12].scale.set(medPoleThickness, 0.25, medPoleThickness);
                    windmill_poles[12].rotation.y = THREE.Math.degToRad( 0 );
                    windmill_poles[12].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[13].position.set(4, -maxHeight/3, 0);
                    windmill_poles[13].scale.set(medPoleThickness, 0.25, medPoleThickness);
                    windmill_poles[13].rotation.y = THREE.Math.degToRad( 90 );
                    windmill_poles[13].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[14].position.set(-4, -maxHeight/3, 0);
                    windmill_poles[14].scale.set(medPoleThickness, 0.25, medPoleThickness);
                    windmill_poles[14].rotation.y = THREE.Math.degToRad( 90 );
                    windmill_poles[14].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[15].position.set(0, -maxHeight/3, -4);
                    windmill_poles[15].scale.set(medPoleThickness, 0.25, medPoleThickness);
                    windmill_poles[15].rotation.y = THREE.Math.degToRad( 0 );
                    windmill_poles[15].rotation.z = THREE.Math.degToRad( 90 );

                    windmill_poles[16].position.set(0, -maxHeight/3, 0);
                    windmill_poles[16].scale.set(intersectPoleThickness, 0.35, intersectPoleThickness);
                    windmill_poles[16].rotation.y = THREE.Math.degToRad( -45 );
                    windmill_poles[16].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[17].position.set(0, -maxHeight/3, 0);
                    windmill_poles[17].scale.set(intersectPoleThickness, 0.35, intersectPoleThickness);
                    windmill_poles[17].rotation.y = THREE.Math.degToRad( 45 );
                    windmill_poles[17].rotation.z = THREE.Math.degToRad( 90 );

                    windmill_poles[18].position.set(0, maxHeight/3, 0);
                    windmill_poles[18].scale.set(intersectPoleThickness, 0.20, intersectPoleThickness);
                    windmill_poles[18].rotation.y = THREE.Math.degToRad( -45 );
                    windmill_poles[18].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[19].position.set(0, maxHeight/3, 0);
                    windmill_poles[19].scale.set(intersectPoleThickness, 0.20, intersectPoleThickness);
                    windmill_poles[19].rotation.y = THREE.Math.degToRad( 45 );
                    windmill_poles[19].rotation.z = THREE.Math.degToRad( 90 );

                    windmill_poles[20].position.set(0, maxHeight/2, 0);
                    windmill_poles[20].scale.set(intersectPoleThickness, 0.15, intersectPoleThickness);
                    windmill_poles[20].rotation.y = THREE.Math.degToRad( -45 );
                    windmill_poles[20].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles[21].position.set(0, maxHeight/2, 0);
                    windmill_poles[21].scale.set(intersectPoleThickness, 0.15, intersectPoleThickness);
                    windmill_poles[21].rotation.y = THREE.Math.degToRad( 45 );
                    windmill_poles[21].rotation.z = THREE.Math.degToRad( 90 );
                    windmill_poles.forEach((pole) => { windmill.add( pole ); });

                windmill.name = 'windmill';

                function mkWindmill( pos, quat ) {
                    var p = windmill.clone();
                    var sx = 6;
                    var sy = maxHeight/2;
                    var sz = 6;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );
                    var windmillBody = createRigidBody( p, shape, 100, pos, quat );
                    windmillBody.setFriction( 1 );
                    scene.add( p );
                }

                //--------------------------------------------------------------------------------------------


                //--------------------------------------------------------------------------------------------
                // toilet block model
                //--------------------------------------------------------------------------------------------

                toiletBlocks = new THREE.Group();
                toiletBlock = new THREE.Group();
                var toiletBlock_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(8, 9, 15.5),
                        setMtl({
                            map: setTx({
                                texture: woodTextures[1],
                                reptx: 1,
                                repty: 1,
                                anisotropy: 16
                            })
                        })
                    );
                    toiletBlock_obj.castShadow = true;
                    toiletBlock_obj.receiveShadow = false;
                    toiletBlock_obj.position.set(0, 0, 0);
                toiletBlock.add( toiletBlock_obj );
                var toiletBlock_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(3, 3, 12),
                        setMtl({
                            color: 0xb4b4b4
                        })
                    );
                    toiletBlock_obj.castShadow = true;
                    toiletBlock_obj.receiveShadow = false;
                    toiletBlock_obj.position.set(0, 3.5, 0);
                toiletBlock.add( toiletBlock_obj );
                toiletSignTx.rotation = THREE.Math.degToRad( 90 );
                var signMtl = setMtl({
                    map: setTx({
                        texture: toiletSignTx,
                        reptx: 1,
                        repty: 1,
                        anisotropy: 16
                    })
                });
                var whiteTx = setMtl({ color: 0xffffff });
                var toiletSignMtls = []
                for(var count=0; count<6; count++) {
                    if(count==2) {
                        toiletSignMtls.push( signMtl );
                    } else {
                        toiletSignMtls.push( whiteTx );
                    }
                }
                var toiletBlock_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(6, 3, 7),
                        toiletSignMtls
                    );
                    toiletBlock_obj.castShadow = true;
                    toiletBlock_obj.receiveShadow = false;
                    toiletBlock_obj.position.set(0, 3.7, 0);
                toiletBlock.add( toiletBlock_obj );
                var toiletBlock_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.2, 6, 3),
                        setMtl({
                            color: 0xb4b4b4
                        })
                    );
                    toiletBlock_obj.castShadow = true;
                    toiletBlock_obj.receiveShadow = false;
                    toiletBlock_obj.position.set(4.3, 1, 3);
                toiletBlock.add( toiletBlock_obj );
                var toiletBlock_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(0.2, 6, 3),
                        setMtl({
                            color: 0xb4b4b4
                        })
                    );
                    toiletBlock_obj.castShadow = true;
                    toiletBlock_obj.receiveShadow = false;
                    toiletBlock_obj.position.set(4.3, 1, -3);
                toiletBlock.add( toiletBlock_obj );
                function mkToiletBlock( pos, quat ) {
                    var p = toiletBlock.clone();
                    var sx = 6;
                    var sy = 4.5;
                    var sz = 7.75;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );
                    var toiletBlockBody = createRigidBody( p, shape, 10, pos, quat );
                    toiletBlockBody.setFriction( 1 );
                    scene.add( p );
                }

                //--------------------------------------------------------------------------------------------


                //--------------------------------------------------------------------------------------------
                // box trailer model
                //--------------------------------------------------------------------------------------------

                var boxTrailer = new THREE.Group();
                var boxTrailer_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(9, 10, 13.5),
                        setMtl({
                            color: 0xffffff
                        })
                    );
                    boxTrailer_obj.castShadow = true;
                    boxTrailer_obj.receiveShadow = false;
                    boxTrailer_obj.position.set(0, 0, 0);
                boxTrailer.add( boxTrailer_obj );
                var boxTrailer_obj = //top panel
                    new THREE.Mesh(
                        new THREE.BoxBufferGeometry(7, 1.5, 2),
                        setMtl({
                            color: 0xb4b4b4
                        })
                    );
                    boxTrailer_obj.castShadow = true;
                    boxTrailer_obj.receiveShadow = false;
                    boxTrailer_obj.position.set(0, 3.5, 6.5);
                boxTrailer.add( boxTrailer_obj );
                var board = mkChalkboard(7, false);
                board.position.set(4.5, 2, 0);
                board.rotation.y = Math.PI/2;
                boxTrailer.add( board );
                function mkBoxTrailer( pos, quat ) {
                    var p = boxTrailer.clone();
                    var sx = 4.5;
                    var sy = 4.5;
                    var sz = 8;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );

                    var boxTrailerBody = createRigidBody( p, shape, 10, pos, quat );
                    boxTrailerBody.setFriction( 1 );
                    scene.add( p );
                }


				function mkBox(width,height,depth,x1,y1,z1, rs=false, cs=true) {
					var gg = new THREE.BoxBufferGeometry( width, height, depth );
					var mesh = new THREE.Mesh( gg, new THREE.MeshLambertMaterial( {  }) );
					mesh.position.y = y1;
					mesh.position.x = x1;
					mesh.position.z = z1;
					mesh.receiveShadow = rs;
					mesh.castShadow = cs;
					// scene.add( mesh );
					return mesh;
				}

				function mkCube(width,height,depth,mtls,rs=true,cs=false) {
					var gg = new THREE.BoxBufferGeometry( width, height, depth );
					var mesh = new THREE.Mesh( gg, mtls );
					mesh.receiveShadow = rs;
					mesh.castShadow = cs;
					return mesh;
				}

				function mkStall(x, y, z, hasAwning) {

					// poles
                    var offset = 3.5;
					var poleGeo = new THREE.BoxBufferGeometry( 0.5, 3.75, 0.5 );
					var poleMat = new THREE.MeshLambertMaterial( {  });
					var sideWidth = 4;
                    var group = new THREE.Group();
                    var stallFrame = new THREE.Group();
					var stallFurniture = new THREE.Group();

					var size = 9;
					var poleWidth = 0.1;
					var height = 5;

						var stallFloor = mkCube(size, 0.08, size, new THREE.MeshLambertMaterial({ color: 0x5698ff }) );
						stallFloor.position.set(0, 0.03-offset, 0);
						var geometry = new THREE.CylinderGeometry( 1, 5.7, 3, 4 );
						var cylinderTexture = fabrics[Math.floor(Math.random()*4)+1];

						cylinderTexture.wrapS = THREE.RepeatWrapping;
						cylinderTexture.wrapT = THREE.RepeatWrapping;
						cylinderTexture.repeat.set( 10, 2 );
						cylinderTexture.anisotropy = 16;
						cylinderTexture.encoding = THREE.sRGBEncoding;
						var cylinderMaterial = new THREE.MeshLambertMaterial( {
							map: cylinderTexture,
							side: THREE.DoubleSide,
							color: 0xc4e7ff,
							alphaTest: 0.2
						} );
						var cylinder = new THREE.Mesh( geometry, cylinderMaterial );
						cylinder.traverse( function ( child ) {

							if ( child instanceof THREE.Mesh ) {

								child.castShadow = false;
								child.receiveShadow = true;

							}

						} );
						cylinder.rotation.y = Math.PI/4
						cylinder.position.z = 0;
						cylinder.position.y = 7-offset;
						cylinder.castShadow = true;
						cylinder.catchShadow = true;
						stallFrame.add( cylinder );
						stallFrame.add( mkBox(poleWidth*2, 0.05, poleWidth*2, 4, -0.03-offset, -4) );
						stallFrame.add( mkBox(poleWidth*2, 0.05, poleWidth*2, -4, -0.03-offset, -4) );
						stallFrame.add( mkBox(poleWidth*2, 0.05, poleWidth*2, 4, -0.03-offset, 4) );
						stallFrame.add( mkBox(poleWidth*2, 0.05, poleWidth*2, -4, -0.03-offset, 4) );
						stallFrame.add( mkBox(poleWidth, height, poleWidth, 4, height/2-offset, -4) );
						stallFrame.add( mkBox(poleWidth, height, poleWidth, -4, height/2-offset, -4) );
						stallFrame.add( mkBox(poleWidth, height, poleWidth, 4, height/2-offset, 4) );
						stallFrame.add( mkBox(poleWidth, height, poleWidth, -4, height/2-offset, 4) );

						switch(Math.floor(Math.random()*4)) {
							case 0:
								var table = mkCube(2.00, 2.50, 8.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(-3.00, 1.00-offset, 0);
								table.name = 'table';
								stallFurniture.add( table );
							break;
							case 1:
								var table = mkCube(2.00, 2.50, 8.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(3.00, 1.00-offset, 0);
								table.name = 'table';
								stallFurniture.add( table );
								var table = mkCube(6.00, 2.50, 2.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(-1.00, 1.00-offset, -3.00);
								table.name = 'table';
								stallFurniture.add( table );
								var table = mkCube(6.00, 2.50, 2.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(-1.00, 1.00-offset, 3.00);
								table.name = 'table';
								stallFurniture.add( table );
							break;
							case 2:
								var table = mkCube(2.00, 2.50, 8.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(3.00, 1.00-offset, 0);
								table.name = 'table';
								stallFurniture.add( table );
								var table = mkCube(6.00, 2.50, 2.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(-1.00, 1.00-offset, -3.00);
								table.name = 'table';
								stallFurniture.add( table );
							break;
							case 3:
								var table = mkCube(2.00, 2.50, 8.00, setMtls(fabrics[Math.floor(Math.random()*5)], 2, 1));
								table.position.set(-3.00, 1.00-offset, 0);
								table.name = 'table';
								stallFurniture.add( table );
                                var board = new THREE.Group();
                                var tx = chalkboardTextures[rndPos(chalkboardTextures.length)];
                                var board_obj = //top panel
                                    new THREE.Mesh(
                                        new THREE.BoxBufferGeometry(0.1, 2.7, 1.8),
                                        setMtl({
                                            map:setTx({
                                                texture:tx,
                                                reptx: 1,
                                                repty: 1,
                                                anisotropy: 16
                                            })
                                        })
                                    );
                                    board_obj.castShadow = true;
                                    board_obj.receiveShadow = false;
                                    board_obj.position.set(-4.20, 2.45-offset, 2.80);
                                    board_obj.scale.set(1.3,1.3,1.3);
                                board.add( board_obj );
								stallFurniture.add( board );

							break;
						}

						var material1 = new THREE.MeshBasicMaterial( { color: 0x144bd5, side: THREE.DoubleSide } );
						var material2 = new THREE.MeshBasicMaterial( { color: 0x1a81e1, side: THREE.DoubleSide } );
						var material3 = new THREE.MeshBasicMaterial( { color: 0x144bd5, side: THREE.DoubleSide } );

						var materialTransparent =  new THREE.MeshBasicMaterial( { transparent: true, opacity: 0, wireframe: true, side: THREE.DoubleSide} );
						var geometry = new THREE.BoxBufferGeometry( 8.30, 1.00, 8.30 );

						var materials = [ cylinderMaterial, cylinderMaterial, cylinderMaterial, materialTransparent, cylinderMaterial, cylinderMaterial ]

						var mesh = new THREE.Mesh( geometry, materials );

						mesh.position.y = 5.00-offset;

						stallFrame.add( mesh );

						if(hasAwning==2) {
							var awning = new THREE.Group();
							var ex = 150;
							awning.add( mkBox(8, 5, 8, 375, -10+ex, 200) );
							awning.add( mkBox(8, 5, 8, 125, -10+ex, 200) );
							awning.add( mkBox(8, 5, 8, -125, -10+ex, 200) );
							awning.add( mkBox(8, 5, 8, -375, -10+ex, 200) );
							awning.add( mkBox(750, 4, 4, 0, -10+ex, 200) );
							awning.add( mkBox(4, 4, 240, 375, -10+ex, 80) );
							awning.add( mkBox(4, 4, 260, 375, 38+ex, 83).rotateX(Math.PI/8) );
							awning.add( mkBox(4, 4, 240, -375, -10+ex, 80) );
							awning.add( mkBox(4, 4, 260, -375, 38+ex, 83).rotateX(Math.PI/8) );

							awning.add( mkCloth(0, 150+ex, -145, -1.14) );

							awning.position.y = 200;
							awning.position.x = - 440;
							awning.rotation.y = - Math.PI/2;
							awning.name = 'awning';
							stallFrame.add( awning );
						}


                    stallSelectFrame = new THREE.Mesh(
                        new THREE.BoxBufferGeometry(10, 8, 10),
                        setMtl({
                            color: 0xffffff,
                            wireframe: true
                        })
                    );
                    stallSelectFrame.castShadow = false;
                    stallSelectFrame.receiveShadow = false;
                    stallSelectFrame.visible = false;
                    stallSelectFrame.position.set(0, 0, 0);

                    group.name = 'stall';
                    stallFloor.name = 'stallFloor';
                    stallSelectFrame.name = 'stallSelectFrame';
                    stallFrame.name = 'stallFrame';
                    stallFurniture.name = 'stallFurniture';

                    stallFrame.add( stallFurniture );
                    group.add( stallFrame );
                    group.add( stallSelectFrame );
                    group.add( stallFloor );

					stalls.push(group);


					return group;

				}


                quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), -30 * Math.PI / 360 ); //flat shape vertical
                mkBoxTrailer( new THREE.Vector3(38, 10, -34), quat );

                quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), -80 * Math.PI / 360 ); //flat shape vertical
                mkWindmill( new THREE.Vector3(99, 10, -112), quat );
                quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), 0 * Math.PI / 360 ); //flat shape vertical
                mkToiletBlock( new THREE.Vector3(-26, 3, 0), quat );
                mkToiletBlock( new THREE.Vector3(22, 3, -98), quat );
                quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), -180 * Math.PI / 360 ); //flat shape vertical
                mkToiletBlock( new THREE.Vector3(71, 3, 73), quat );

                // building



                var _x = -62.00;
                var _y = 0;
                var _z = -6.00;

                var building_1 = new THREE.Group();

                var ml_texture = marketLogoTextures[0].clone();
                ml_texture.wrapT = THREE.RepeatWrapping;
                ml_texture.wrapS = THREE.RepeatWrapping;
                ml_texture.repeat.set( 1, 1 );
                ml_texture.offset.set(0,0);
                ml_texture.anisotropy = 16;
                ml_texture.needsUpdate = true;
                // controls.target = (scene.getObjectByName('windmill').position);
                quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 180 * Math.PI / 360 ); //flat shape vertical
                var building_underlay = mkShape(0, new THREE.Vector3(0, -2.10, 0), quat, setMtls(bricks[1], 0.15, 0.08), [[-25,-2],[-25,-63],[-94,-63],[-94,-2]]);
                var building_underlay2 = mkShape(0, new THREE.Vector3(0, -2.15, 0), quat, setMtls(stones[0], 0.1, 0.1), [[-23,-0],[-23,-65],[-96,-65],[-96,-0]]);
                building_1.add( createParalellepiped(10, .10, 10.0, 0, new THREE.Vector3(-48.00, 5, 24), quat, new THREE.MeshPhongMaterial({ map: ml_texture, transparent: true }), false, false) );//logo
                var building_underlay3 = mkShape(0, new THREE.Vector3(0, -2.05, 0), quat, setMtls(concreteTextures[0], 0.1, 0.1), [[-33,28.5],[-69,28.5],[-69,-25],[-33,-25]]);
                building_1.add( createParalellepiped(4, 2.00, .50, 0, new THREE.Vector3(-65.00, -1.5, 26.00), quat, setMtls(concreteTextures[0], 10, 10), false, true) );//stairs
                building_1.add( createParalellepiped(4, 2.00, .50, 0, new THREE.Vector3(-65.00, -1, 25.00), quat, setMtls(concreteTextures[0], 10, 10), false, true) );//stairs
                building_1.add( createParalellepiped(2, 4.00, .50, 0, new THREE.Vector3(-35.00, -1.5, -22.00), quat, setMtls(concreteTextures[0], 10, 10), false, true) );//stairs
                building_1.add( createParalellepiped(2, 4.00, .50, 0, new THREE.Vector3(-36.00, -1, -22.00), quat, setMtls(concreteTextures[0], 10, 10), false, true) );//stairs
                // quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), - 180 * Math.PI / 360 ); //flat shape vertical
                // var roof_overlay1 = mkShape(0, new THREE.Vector3(0, 11.6, 0), quat, new THREE.MeshPhongMaterial({ map: ml_texture, transparent: true }), [[-74,0],[-59,0],[-59,-15],[-74,-15]]);//roof overlay
                building_1.add( building_underlay );
                building_1.add( building_underlay2 );
                building_1.add( building_underlay3 );
                // building_1.add( roof_overlay1 );
                quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), 0 * Math.PI / 360 ); //flat shape vertical
                building_1.add( createParalellepiped(20.00, 20.00, 40.00, 0, new THREE.Vector3(-47.00, 0, 4.00), quat, setMtls(bricks[3], 10, 10), true, false) );//building
                building_1.add( createParalellepiped(50.40, 3.00, 60.40, 0, new THREE.Vector3(-62.00, -2, -6.00), quat, setMtls(concreteTextures[1], 2, 20), true, false) );//foundation
                building_1.add( createParalellepiped(51.00, 1.00, 41.00, 0, new THREE.Vector3(-62.00, 10.00, 4.00), quat, setMtls(concreteTextures[1], 25, 25), true, true) );//roof

                buildingSelectFrame = new THREE.Mesh(
                    new THREE.BoxBufferGeometry(52.4, 14, 62.4),
                    setMtl({
                        color: 0xffffff,
                        // linewidth: 3,
                        wireframe: true
                    })
                );
                buildingSelectFrame.castShadow = false;
                buildingSelectFrame.receiveShadow = false;
                buildingSelectFrame.visible = false;
                buildingSelectFrame.selected = false;
                buildingSelectFrame.position.set(-62.00, 5.2, -6.00);

                buildingSelectFrame.name = 'buildingSelectFrame';

                building_1.add( buildingSelectFrame );
                //
                // //corner posts
                building_1.add( createParalellepiped(0.30, 19.90, 0.30, 0, new THREE.Vector3(-37.00, 0, -36.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );
                building_1.add( createParalellepiped(0.30, 19.90, 0.30, 0, new THREE.Vector3(-87.00, 0, -36.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );
                building_1.add( createParalellepiped(0.30, 19.90, 0.30, 0, new THREE.Vector3(-37.00, 0, 24.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );
                building_1.add( createParalellepiped(0.30, 19.90, 0.30, 0, new THREE.Vector3(-87.00, 0, 24.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );
                //
                // //top beams
                building_1.add( createParalellepiped(0.40, 0.48, 30.40, 0, new THREE.Vector3(-87.00, 10.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//edge
                building_1.add( createParalellepiped(0.40, 0.48, 30.40, 0, new THREE.Vector3(-37.00, 10.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//edge
                building_1.add( createParalellepiped(50.00, 0.48, 0.40, 0, new THREE.Vector3(-62.00, 10.00, -36.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//edge


                quat.setFromAxisAngle( new THREE.Vector3( 0, 0, 1 ), 100 * Math.PI / 360 ); //flat shape vertical
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-85.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-8300, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-81.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-7900, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-77.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-7500, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-73.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-7100, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-69.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-6700, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-65.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-6300, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-61.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-5900, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-57.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-5500, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-53.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-5100, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-49.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-4700, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-45.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-4300, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                building_1.add( createParalellepiped(0.20, 1.90, 30.00, 0, new THREE.Vector3(-41.00, 9.00, -21.00), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice
                // building_1.add( createParalellepiped(20, 90, 3000, 0, new THREE.Vector3(-3900, 950, -2100), quat, setMtls(woodTextures[0], 1, 1), false, true) );//lattice

                var ban = banana_obj.clone();
                ban.rotation.x = Math.PI/8;
                ban.rotation.y = (Math.PI/2)*330/90;
                var s = 4.00;
                ban.scale.set(s,s,s);
                ban.receiveShadow = false;
                ban.castShadow = true;
                ban.position.x -= 52.00;
                ban.position.y = 16.00;
                ban.position.z = 6.00;
                building_1.add( ban );

                building_1.name = 'main_building';

                scene.add( building_1 );

    			function mkNewNpc(x, y, z, origin) {

                    // Creates a ball
                    var ballMass = 3;
                    var ballRadius = 1.00;

                    var ball = new THREE.Mesh( new THREE.SphereBufferGeometry( ballRadius, 10, 10 ), setMtls(woodTextures[1], 0.5, 0.5) );
                    ball.castShadow = true;
                    ball.receiveShadow = true;
                    var ballShape = new Ammo.btSphereShape( ballRadius );
                    ballShape.setMargin( margin );
                    // pos.copy( rayCaster.ray.direction );
                    // pos.add( rayCaster.ray.origin );
                    pos.set(x, y, z);
                    quat.set( 0, 0, 0, 1 );
                    var ballBody = createRigidBody( ball, ballShape, ballMass, pos, quat );
                    ballBody.setFriction( 1 );

                    pos.set( (Math.random()*3.00)-1.50, 1.00, (Math.random()*3.00)-1.50 );
                    pos.multiplyScalar( 3 );
                    ballBody.setLinearVelocity( new Ammo.btVector3( pos.x, pos.y, pos.z ) );

                    var newNpc = ball;
    				newNpc.targetStalls = [];
    				for(var p=0; p<10; p++) {
    					newNpc.targetStalls.push(stalls_group.children[Math.floor(Math.random()*(stalls_group.children.length))]);
    				}
    				newNpc.targetDestinations = [];
    				newNpc.targetDestinations.push( origin );
    				newNpc.targetDestinations.push( { x:0, z:-500.00 } );
    				newNpc.startDelay = Math.floor(Math.random()*10000);
    				newNpc.npc_speed = npc_speed+(Math.random()/4);
    				newNpc.tweens = [];
    				newNpc.steps = [];
    				newNpc.status = 'stationary';

    				return newNpc;
    			}

                function addNpcs(n) {
                    for(var x=0; x<n; x++) {
                        npcs.add( mkNewNpc( (Math.random()*100.00)-50.00, 20.00+(Math.random()*20.00), (Math.random()*100.00)-50.00, { x:0, z: -100.00 } ) );
                    }
                    console.log("added NPCs");
                }

                function mkChalkboard(cb, base=true) {

                    var rp, rot;
                    rp = [1,1];
                    switch(cb) {
                        case -1:
                        return mkChalkboard( rndNeg((chalkboardTextures.length)+chalkboardTextures.length) );
                        break;
                        case 7:
                        rot = true;
                        case 2:
                        rot = true;
                        break;
                        default:
                        break;
                    }
                    var board = new THREE.Group();
                    var board_obj = //top panel
                        new THREE.Mesh(
                            new THREE.BoxBufferGeometry(1.8, 2.7, 0.1),
                            setMtl({
                                map:setTx({
                                    texture:chalkboardTextures[cb],
                                    reptx: rp[0],
                                    repty: rp[1],
                                    anisotropy: 16
                                })
                            })
                        );
                        board_obj.castShadow = true;
                        board_obj.receiveShadow = false;
                        board_obj.position.set(0, 1.35, 0);
                        if(rot) {
                            board_obj.rotation.z = 180 * Math.PI/360;
                            board_obj.material.map.rotation = Math.PI/2;
                        }
                    board.add( board_obj );
                    if(base) {

                        var chalkboardBase_obj = //top panel
                            new THREE.Mesh(
                                new THREE.BoxBufferGeometry(2, 0.4, 1),
                                setMtl({
                                    map:setTx({
                                        texture:woodTextures[1]
                                    })
                                })
                            );
                            chalkboardBase_obj.castShadow = true;
                            chalkboardBase_obj.receiveShadow = false;
                            chalkboardBase_obj.position.set(0, 0.1, 0);

                        board_obj.position.y = 1.7;
                        board.add( chalkboardBase_obj );
                    }
                    board.scale.set(3,3,3);

                    return board;

                }

    			function mkBanana(pos, quat) {

                    // Creates a ball
                    var bananaMass = 1;
                    var bananaRadius = 1.00;


                    var banana = banana_obj.clone();
                    // banana.rotation.x = Math.PI/8;
                    // banana.rotation.y = (Math.PI/2)*330/90;
                    var s = 0.20;
                    banana.scale.set(s,s,s);
                    banana.receiveShadow = true;
                    banana.castShadow = true;
                    var bananaShape = new Ammo.btSphereShape( bananaRadius );
                    bananaShape.setMargin( margin );
                    // pos.copy( rayCaster.ray.direction );
                    // pos.add( rayCaster.ray.origin );
                    var bananaBody = createRigidBody( banana, bananaShape, bananaMass, pos, quat );
                    bananaBody.setFriction( 1 );

                    pos.set( rndNeg(2.00), 1.00, rndNeg(2.00) );
                    pos.multiplyScalar( 3 );
                    bananaBody.setLinearVelocity( new Ammo.btVector3( pos.x, pos.y, pos.z ) );

    				return banana;

    			}

                function addBananas(n) {
                    for(var x=0; x<n; x++) {
                        pos = new THREE.Vector3(rndNeg(0.20)-50.00, 20.00+rndNeg(5.00), rndNeg(0.20)-30.00);
                        bananas.add( mkBanana( pos, quat ) );
                    }
                    console.log("added bananas");
                }


                scene.add( bananas );
                scene.add( npcs );

                var chalkboards = [];

                for(var f=0; f<chalkboardTextures.length; f++) {
                    chalkboards.push( mkChalkboard(f) );
                }
                var f=0;
                var chalkboardsGroup = new THREE.Group();
                chalkboardsGroup.name = 'chalkboards';
                var pts = [[-28,38,0,60],[28,43,1,-70],[72,-76,2,90],[62,-76,5,-90],[-124,-84,3,90],[-114,66,4,-90]];
                pts.forEach((vect) => {
                    f++;
                    pos.set(vect[0], -2, vect[1]);
                    quat.setFromAxisAngle( new THREE.Vector3(0, 1, 0), vect[3] * Math.PI/360 );
                    var b = chalkboards[vect[2]];
                    b.name = 'chalkboard';
                    chalkboardsGroup.add( b );
                    var sx = 2.7;
                    var sy = 8.1;
                    var sz = 0.1;
                    var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
                    shape.setMargin( margin );
                    createRigidBody( b, shape, 0, pos, quat );
                });
                scene.add( chalkboardsGroup );


                var canvas = renderer.domElement;
                var canvasPosition = $(canvas).position();

                var moveMarker = false;

                function deselectStalls() {
                    stalls_group.children.forEach((stall) => {
                        // if(stall.name=="stall") {
                            if(stall.selected) {
                                stall.children.forEach((child) => {
                                    child.material = child.originalMaterial;
                                    if ( child instanceof THREE.Group ) {
                                        child.children.forEach((c) => {
                                            c.material = c.originalMaterial;
                                        });
                                    }
                                });
                                moveMarker = true;
                            }
                            stall.selected = false;
                        // }
                    });
                    marker.position.y = -10000.00;
                    marker.rotation.x = Math.PI;
                    marker.position.needsUpdate = true;
                    marker.rotation.needsUpdate = true;
                    check_stall_selected();
                }

                function getClicked3DPoint(evt) {
                    if(!cancelClick) {
                        evt.preventDefault();

                        mousePosition.x = ((evt.clientX - canvasPosition.left) / canvas.width) * 2 - 1;
                        mousePosition.y = -((evt.clientY - canvasPosition.top) / canvas.height) * 2 + 1;

                        if(evt.type=="touchend") {
                            mousePosition.x = (event.changedTouches[0].clientX / window.innerWidth) * 2 - 1;
                            mousePosition.y = -(event.changedTouches[0].clientY / window.innerHeight) * 2 + 1;
                        }

                        rayCaster.setFromCamera(mousePosition, camera);
                        var intersects = rayCaster.intersectObjects(scene.getObjectByName('floor').children, true);
                        var stall_intersects = rayCaster.intersectObjects(scene.getObjectByName('stalls_group').children, true);
                        var building_intersects = rayCaster.intersectObjects(scene.getObjectByName('main_building').children, true);
                        var building_click = (building_intersects.length) > 0 ? building_intersects[0] : null;

                        if(building_click) {
                            var b = scene.getObjectByName('buildingSelectFrame');
                            if(!b.selected) {
                                b.material = setMtl({ color:0x0064ff, wireframe: true });
                            } else {
                                b.material = setMtl({ color:0xffffff, wireframe: true });
                            }
                            b.material.needsUpdate = true;
                            b.selected = (!b.selected);
                            deselectStalls();
                        } else if(stall_intersects.length > 0) {
                            // consoleLog(stall_intersects[0]);
                            var inter = stall_intersects[0].object;
                            // if(inter.parent.name=="stall") {
                                if(inter.parent.name!='stall') {
                                    inter = stall_intersects[0].object.parent;
                                }
                                var s = inter.parent;
                                if(s.selected===undefined) { s.selected = false; }
                                if(s.selected) {
                                    s.children.forEach((child) => {
                                        child.material = child.originalMaterial;
                                        if ( child instanceof THREE.Group ) {
                                            child.children.forEach((c) => {
                                                c.material = c.originalMaterial;
                                            });
                                        }
                                    });
                                    s.selected = false;
                                    marker.position.y = -10000.00;
                                    marker.rotation.x = Math.PI;
                                    marker.position.needsUpdate = true;
                                    marker.rotation.needsUpdate = true;
                                } else {
                                    moveMarker = false;
                                    deselectStalls();
                                    if(scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame')!==undefined) {
                                        scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame').material = setMtl({ color:0xffffff, wireframe: true });
                                        scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame').selected = false;
                                        scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame').visible = false;
                                    }
                                    s.getObjectByName("stallSelectFrame").visible = false;
                                    s.children.forEach((child) => {
                                        child.originalMaterial = child.material;
                                        child.material = new THREE.MeshLambertMaterial({ color: 0xffd62b });
                                        if ( child instanceof THREE.Group ) {
                                            child.children.forEach((c) => {
                                                c.originalMaterial = c.material;
                                                c.material = new THREE.MeshLambertMaterial({ color: 0xffd62b });
                                            });
                                        }
                                    });
                                    s.selected = true;
                                    marker.position.y = 5.00;
                                    marker.rotation.x = Math.PI/2;
                                    if(moveMarker) {
                                        var position = { x:marker.position.x, z:marker.position.z };
                                        var target = { x:s.position.x, z:s.position.z };
                                        var tween = new TWEEN.Tween(position).to(target, 200);
                                        tween.easing(TWEEN.Easing.Quadratic.InOut)
                                        tween.onUpdate(function(){
                                            marker.position.x = position.x;
                                            marker.position.z = position.z;
                                        });
                                        tween.start();
                                    } else {
                                        marker.position.set(s.position.x, 0, s.position.z);
                                    }

                                }
                                check_stall_selected();
                            // }
                        } else if (intersects.length > 0) {

                            if(intersects[0].point!==undefined) {

                                if(save2dClick) {
                                    capturedShape.push([Math.floor(intersects[0].point.x),Math.floor(intersects[0].point.z)]);
                                    consoleLog('Added point to shape ['+Math.floor(intersects[0].point.x)+','+Math.floor(intersects[0].point.z)+']');
                                    $(".stop-2d-shape-capture").removeClass('disabled').attr("disabled", false);
                                    $(".stop-2d-shape-capture .points-text").html('Copy <span>'+capturedShape.length+'</span> points');
                                    $(".stop-2d-shape-capture .points-icon").removeClass('fa-mouse-pointer').addClass('fa-copy');
                                } else {
                                    consoleLog('['+Math.floor(intersects[0].point.x)+','+Math.floor(intersects[0].point.z)+']');
                                    switch(clickCreate) {
                                        case "npc":
                                            npcs.add( mkNewNpc( intersects[0].point.x, 10, intersects[0].point.z, { x:0, z: -100.00 } ) );
                                        break;
                                        case "banana":
                                            for(var g=0; g<20; g++) {
                                                bananas.add( mkBanana( new THREE.Vector3(intersects[0].point.x, 10, intersects[0].point.z), quat ) );
                                            }
                                        break;
                                        case "chair":
                                            pos.set(intersects[0].point.x, 10, intersects[0].point.z);
                                            quat.setFromAxisAngle( new THREE.Vector3( 1, 1, 0 ), (rndNeg(15)) * Math.PI / 360 );
                                            mkNewChair(pos, quat);
                                        break;
                                        case "table":
                                            pos.set(intersects[0].point.x, 10, intersects[0].point.z);
                                            quat.setFromAxisAngle( new THREE.Vector3( 0, 0, 1 ), (rndNeg(15)) * Math.PI / 360 );
                                            mkNewTable(pos, quat);
                                        break;
                                        case "pallet":
                                            var _angle = rndNeg(180);
                                            var top = rndPos(6);
                                            for(var g=0; g<top; g++) {
                                                pos.set(intersects[0].point.x, (10+(g*0.6)), intersects[0].point.z);
                                                quat.setFromAxisAngle( new THREE.Vector3( 0, 1, 0 ), (_angle+rndNeg(15)) * Math.PI / 360 ); //flat shape horizontal
                            					mkPallet(pos, quat);
                                            }
                                        break;
                                        case "bin":
                                            pos.set(intersects[0].point.x, 10, intersects[0].point.z);
                                            quat.setFromAxisAngle( new THREE.Vector3( 1, 0, 0 ), (rndNeg(15)) * Math.PI / 360 );
                                            mkNewBin(pos, quat);
                                        break;
                                    }
                                }
                                deselectStalls();
                                var b = scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame');
                                scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame').material = setMtl({ color:0xffffff, wireframe: true });
                                scene.getObjectByName('main_building').getObjectByName('buildingSelectFrame').selected = false;
                                consoleLog("Deselect all");
                            } else {
                                consoleLog("Undefined click");
                            }
                        }
                    }
                };

                var clickCreate = false;
                var cancelClick = false;

                var touchMoveCount = 0;
                var clickCount = 0;

                $(renderer.domElement).on("mousedown", function(){
                    cancelClick = false;
                });
                $(renderer.domElement).on("mouseup", function(){
                    clickCount = 0;
                    cancelClick = false;
                });
                $(renderer.domElement).on("mousemove", function(evt) {
                    clickCount++;
                    if(clickCount>3) {
                        cancelClick = true;
                        clickCount = 0;
                    }
                    mousePosition.x = ((evt.clientX - canvasPosition.left) / canvas.width) * 2 - 1;
                    mousePosition.y = -((evt.clientY - canvasPosition.top) / canvas.height) * 2 + 1;

                    rayCaster.setFromCamera(mousePosition, camera);
                    var intersects = rayCaster.intersectObjects(scene.getObjectByName('main_building').children, true);
                    var building_hover = (intersects.length) > 0 ? intersects[0] : null;
                    var intersects = rayCaster.intersectObjects(scene.getObjectByName('stalls_group').children, true);
                    var stalls_hover = (intersects.length) > 0 ? intersects[0] : null;

                    var b = scene.getObjectByName('buildingSelectFrame');
                    if(building_hover || b.selected) {
                        b.visible = true;
                    } else {
                        b.visible = false;
                    }
                    if(stalls_hover) {
                        var s = false;
                        intersects.forEach((sect) => {
                            if(sect.object.name=='stallFloor') {
                                s = sect.object.parent;
                            }
                        });
                        while(stalls_group.getObjectByProperty("hovering", true)!==undefined) {
                            stalls_group.getObjectByProperty("hovering", true).getObjectByName("stallSelectFrame").visible = false;
                            stalls_group.getObjectByProperty("hovering", true).hovering = false;
                        }
                        if(s.name=='stall') {
                            if(s.selected===undefined) { s.selected = false; }
                            if(!s.selected) {
                                s.getObjectByName("stallSelectFrame").visible = true;
                                s.hovering = true;
                            } else {
                                s.getObjectByName("stallSelectFrame").visible = false;
                            }
                        }
                    } else {
                        while(stalls_group.getObjectByProperty("hovering", true)!==undefined) {
                            stalls_group.getObjectByProperty("hovering", true).getObjectByName("stallSelectFrame").visible = false;
                            stalls_group.getObjectByProperty("hovering", true).hovering = false;
                        }
                    }
                });
                $(renderer.domElement).on("touchstart", function(){
                    cancelClick = false;
                });
                $(renderer.domElement).on("touchmove", function() {
                    touchMoveCount++;
                    if(touchMoveCount>10) {
                        cancelClick = true;
                        touchMoveCount = 0;
                    }
                });
                $(renderer.domElement).on("touchend", function() {
                    touchMoveCount = 0;
                });
                $(renderer.domElement).on("click", getClicked3DPoint);
                $(renderer.domElement).on("touchend", getClicked3DPoint);
                $(".locate-stall-btn").on("click", function(){
                    function cameraVector() {
                        camera.updateMatrixWorld();
                        rayCaster.setFromCamera(cameraCenter, camera);

                        var intersections = rayCaster.intersectObjects(scene.getObjectByName('floor').children);
                        var intersection = (intersections.length) > 0 ? intersections[0] : null;

                        if(intersection) {
                            // consoleLog('['+Math.floor(intersection.point.x)+','+Math.floor(intersection.point.z)+']');
                            var _x = intersection.point.x;
                            var _y = intersection.point.y;
                            var _z = intersection.point.z;
                            return { x: _x, y: _y, z: _z };
                        }
                        return false;
                    }
                    var _stall = false;
                    stalls_group.children.forEach((stall) => {
                        // if(stall.name=="stall") {
                            if(stall.selected) {
                                _stall = stall;
                                _stall.npcUpcoming = [];
                                npcs.children.forEach((npc) => {
                                    if(npc.target_stall==_stall) {
                                        _stall.npcUpcoming.push(npc);
                                    };
                                });
                            }
                        // }
                    });

                    if(_stall) {
                        var cposition = cameraVector();
                        var ctarget = { x:_stall.position.x, y:_stall.position.y, z:_stall.position.z };
                        ctween = new TWEEN.Tween(cposition).to(ctarget, 1200);
                        ctween.easing(TWEEN.Easing.Quadratic.InOut)
                        ctween.onUpdate(function(){
                            controls.target = new THREE.Vector3(cposition.x, cposition.y, cposition.z);
                        });
                        ctween.start();
                    }
                });
                $(".click-to-create").on("click", function(){
                    if($(this).hasClass("btn-primary")) {
                        clickCreate = false;
                        $(".click-to-create").removeClass("btn-primary").addClass("btn-menu");
                    } else {
                        clickCreate = $(this).data('type');
                        $(".click-to-create").removeClass("btn-primary").addClass("btn-menu");
                        $(this).removeClass("btn-menu").addClass("btn-primary");
                    }
                });
                $(".begin-2d-shape-capture").on("click", begin2dShapeCapture);
                $(".stop-2d-shape-capture").on("click", stop2dShapeCapture);
                $(".open-menu").on("click", function() {
                    var b = $(".open-menu");
                    $(".left-menu").toggleClass("show");
                    $(".control-panel").toggleClass("hide");
                    if($(".menu-container").hasClass("hide")) {
                        stop2dShapeCapture();
                    }
                });
                $(".menu-down").on("click", function(e) {
                    if (!e) var e = window.event;
                	e.cancelBubble = true;
                	if (e.stopPropagation) e.stopPropagation();
                    var b = $(".open-menu");
                    $(".left-menu").toggleClass("show");
                    $(".control-panel").toggleClass("hide");
                    if($(".menu-container").hasClass("hide")) {
                        stop2dShapeCapture();
                    }
                });
                $(".lock-camera-btn").on("click", function() {
                    var b = $(this);
                    b.find("i").toggleClass("fa-lock fa-unlock");
                    b.toggleClass("btn-menu btn-warning text-white");
                    controls.enabled = (!controls.enabled);
                    if(controls.enabled) {
                        controls.enableDamping = true;
                    } else {
                        controls.enableDamping = false;
                    }
                });
                $(".auto-rotate-btn").on("click", function() {
                    var b = $(this);
                    b.find("i").toggleClass("fa-sync-alt fa-times");
                    b.toggleClass("btn-menu btn-warning text-white");
                    controls.autoRotate = (!controls.autoRotate);
                });
                $(".close-stall-btn").on("click", function(){
                    var _stall = false;
                    stalls_group.children.forEach((stall) => {
                        // if(stall.name=="stall") {
                            if(stall.selected) {
                                _stall = stall;
                            }
                        // }
                    });
                    if(_stall) {
                        _stall.slideFurnitureDown.start();
                    }
                });
                $(".open-stall-btn").on("click", function(){
                    var _stall = false;
                    stalls_group.children.forEach((stall) => {
                        // if(stall.name=="stall") {
                            if(stall.selected) {
                                _stall = stall;
                            }
                        // }
                    });
                    if(_stall) {
                        _stall.slideFrameUp.start();
                    }
                });
                $(".new-game-btn").on("click", function(){
                    deleteCookie("mot_id");
                    window.location.reload();
                });
                $(".toggle-div").on("click", function(){

                    $(".to-toggle").not(this).removeClass("hide");
                    $(this).closest("div").find(".to-toggle").toggleClass("hide");

                });


                var save2dClick = false;
                var capturedShape = [];
                function begin2dShapeCapture() {
                    capturedShape = [];
                    save2dClick = true;
                    $(".begin-2d-shape-capture").addClass("hide");
                    $(".stop-2d-shape-capture").removeClass("hide");
                    consoleLog("Begin 2D shape capture");
                    $(".stop-2d-shape-capture .points-text").html('Click map to begin');
                    $(".stop-2d-shape-capture .points-icon").addClass('fa-mouse-pointer').removeClass('fa-copy');
                    $(".stop-2d-shape-capture").addClass('disabled').attr("disabled", true);
                }
                function stop2dShapeCapture() {
                    if(capturedShape.length!==0) {
                        var returnText = '[';
                        var comma = "";
                        capturedShape.forEach((vert) => {
                            returnText = returnText+comma+'['+Math.floor(vert[0])+','+Math.floor(vert[1])+']';
                            comma = ",";
                        });
                        returnText = returnText+']';
                        clip(returnText);
                        consoleLog("Shape copied to clipboard");
                    }
                    save2dClick = false;
                    $(".begin-2d-shape-capture").removeClass("hide");
                    $(".stop-2d-shape-capture").addClass("hide");

                }


                dropoff_text = new THREE.Group();
                dropoff_text.name = 'dropoff_text';
                var dropoff_text_obj = //Base
                    new THREE.Mesh(
                        new THREE.TextBufferGeometry( 'DROP OFF', {
                            font: defaultFont,
                            size: 5,
                            height: 0.005,
                            curveSegments: 3,
                            bevelEnabled: true,
                            bevelThickness: 0.02,
                            bevelSize: 0.03,
                            bevelOffset: 0,
                            bevelSegments: 2
                        } ),
                        setMtl({ color: 0xffffff, transparent: true, opacity:0.6 })
                    );
                    dropoff_text_obj.castShadow = true;
                    dropoff_text_obj.receiveShadow = false;
                    dropoff_text_obj.position.set(0, 0, 0);
                dropoff_text.add( dropoff_text_obj );

                pickup_text = new THREE.Group();
                pickup_text.name = 'pickup_text';
                var pickup_text_obj = //Base
                    new THREE.Mesh(
                        new THREE.TextBufferGeometry( 'PICK UP', {
                            font: defaultFont,
                            size: 5,
                            height: 0.005,
                            curveSegments: 3,
                            bevelEnabled: true,
                            bevelThickness: 0.02,
                            bevelSize: 0.03,
                            bevelOffset: 0,
                            bevelSegments: 2
                        } ),
                        setMtl({ color: 0xffffff, transparent: true, opacity:0.6 })
                    );
                    pickup_text_obj.castShadow = true;
                    pickup_text_obj.receiveShadow = false;
                    pickup_text_obj.position.set(0, 0, 0);
                pickup_text.add( pickup_text_obj );


                // scene.add( dropoff_text );
                dropoff_text.rotation.x = THREE.Math.degToRad( 90 );
                dropoff_text.rotation.z = THREE.Math.degToRad( 270 );
                dropoff_text.rotation.y = THREE.Math.degToRad( 180 );
                dropoff_text.position.set(-15,0.2,63);
                // scene.add( pickup_text );
                pickup_text.rotation.x = THREE.Math.degToRad( 90 );
                pickup_text.rotation.z = THREE.Math.degToRad( 90 );
                pickup_text.rotation.y = THREE.Math.degToRad( 180 );
                pickup_text.position.set(13,0.2,35);

                setTimeout(function(){
                    // stalls_group.children.forEach((stall) => {
                    //     // if(stall.name=="stall") {
                    //         if(stall.owned) {
                    //             stall.nextMovement.delay(Math.floor(Math.random()*2000));
                    //             stall.nextMovement.start();
                    //         }
                    //     // }
                    // });
                    // save_game_to_server();
                }, 200);

			}


            //Stall UI controls
            $(".new-stall-category-btn").on("click", function(){
                //Right panel button choosing new stall category

                //is or is not selected already
                if($(this).hasClass("btn-primary")) {
                    //reset controls
                    $(".new-stall-container").addClass("hide");
                    $(".new-stall-category-btn").not(this).removeClass("hide");
                    $(this).toggleClass("btn-primary btn-menu");
                } else {
                    //expand category
                    $(".new-stall-category-btn").not(this).addClass("hide");
                    var b = $(this);
                    var cid = b.data('cid');
                    var cat_clients = [];
                    localClients.forEach((client) => {
                        if(parseInt(client._cid)==parseInt(cid)) {
                            cat_clients.push(client);
                        }
                    });
                    var stall_1 = cat_clients[0];
                    var stall_2 = cat_clients[1];
                    var stall_3 = cat_clients[2];

                    $(".right-panel .stall-1 .name").html(getClient(cat_clients[0]._clid).MarketClientName);
                    $(".right-panel .stall-2 .name").html(getClient(cat_clients[1]._clid).MarketClientName);
                    $(".right-panel .stall-3 .name").html(getClient(cat_clients[2]._clid).MarketClientName);
                    $(".right-panel .stall-1 .price").html("$2,500");
                    $(".right-panel .stall-2 .price").html("$3,500");
                    $(".right-panel .stall-3 .price").html("$2,750");
                    $(".right-panel .stall-1 .satisfaction-pr").css("width", stall_1.sc._cs+'%');
                    $(".right-panel .stall-1 .margin-pr").css("width", stall_1.sc._pm+'%');
                    $(".right-panel .stall-1 .costs-pr").css("width", stall_1.sc._rc+'%');
                    $(".right-panel .stall-1 .appeal-pr").css("width", stall_1.sc._ca+'%');
                    $(".right-panel .stall-2 .satisfaction-pr").css("width", stall_2.sc._cs+'%');
                    $(".right-panel .stall-2 .margin-pr").css("width", stall_2.sc._pm+'%');
                    $(".right-panel .stall-2 .costs-pr").css("width", stall_2.sc._rc+'%');
                    $(".right-panel .stall-2 .appeal-pr").css("width", stall_2.sc._ca+'%');
                    $(".right-panel .stall-3 .satisfaction-pr").css("width", stall_3.sc._cs+'%');
                    $(".right-panel .stall-3 .margin-pr").css("width", stall_3.sc._pm+'%');
                    $(".right-panel .stall-3 .costs-pr").css("width", stall_3.sc._rc+'%');
                    $(".right-panel .stall-3 .appeal-pr").css("width", stall_3.sc._ca+'%');
                    $(".new-stall-container").removeClass("hide");
                    b.toggleClass("btn-primary btn-menu");

                    if((saveFile.stats.accountBalance-2500)<0) {
                        $(".right-panel .stall-1").addClass("disabled").attr("disabled", true);
                    } else {
                        $(".right-panel .stall-1").removeClass("disabled").attr("disabled", false);
                    }

                    if((saveFile.stats.accountBalance-3500)<0) {
                        $(".right-panel .stall-2").addClass("disabled").attr("disabled", true);
                    } else {
                        $(".right-panel .stall-2").removeClass("disabled").attr("disabled", false);
                    }

                    if((saveFile.stats.accountBalance-2750)<0) {
                        $(".right-panel .stall-3").addClass("disabled").attr("disabled", true);
                    } else {
                        $(".right-panel .stall-3").removeClass("disabled").attr("disabled", false);
                    }

                }

            });
            $(".new-stall-build-btn").on("click", function(){
                //Right panel button choosing new stall to build

                var b = $(this);
                var _client_id = b.data('cid');

                //split stalls into categories
                var cid = $(".new-stall-category-btn.btn-primary").data("cid");
                var cat_clients = [];
                localClients.forEach((client) => {
                    if(parseInt(client._cid)==parseInt(cid)) {
                        cat_clients.push(client);
                    }
                });

                //build cost
                var cost = 0;
                switch(_client_id) {
                    case 0:
                    cost=2500;
                    break;
                    case 1:
                    cost=3500;
                    break;
                    case 2:
                    cost=2750;
                    break;
                }

                //select client
                var _client = cat_clients[_client_id];

                //find selected stall on map
                var _stall = false;
                stalls_group.children.forEach((stall) => {
                    if(stall.selected) {
                        _stall = stall;
                    }
                });

                //apply client to stall
                if(_stall) {
                    _stall.client = _client;
                    _stall.info.time = saveFile.stats.gameTime;
                    saveFile.stats.accountBalance -= cost;
                    _stall.slideFrameUp.start();
                    if(!savedStalls) {
                        savedStalls = [];
                    }
                    savedStalls.push(_stall);
                    setTimeout(function(){ save_game_to_server(); }, 1000);
                }

                //reset controls for next stall
                $(".new-stall-container").addClass("hide");
                $(".new-stall-category-btn").removeClass("hide btn-primary").addClass("btn-menu");

            });



			function processGeometry( bufGeometry ) {

				// Ony consider the position values when merging the vertices
				var posOnlyBufGeometry = new THREE.BufferGeometry();
				posOnlyBufGeometry.setAttribute( 'position', bufGeometry.getAttribute( 'position' ) );
				posOnlyBufGeometry.setIndex( bufGeometry.getIndex() );

				// Merge the vertices so the triangle soup is converted to indexed triangles
				var indexedBufferGeom = BufferGeometryUtils.mergeVertices( posOnlyBufGeometry );

				// Create index arrays mapping the indexed vertices to bufGeometry vertices
				mapIndices( bufGeometry, indexedBufferGeom );

			}

			function isEqual( x1, y1, z1, x2, y2, z2 ) {

				var delta = 0.000001;
				return Math.abs( x2 - x1 ) < delta &&
						Math.abs( y2 - y1 ) < delta &&
						Math.abs( z2 - z1 ) < delta;

			}

			function mapIndices( bufGeometry, indexedBufferGeom ) {

				// Creates ammoVertices, ammoIndices and ammoIndexAssociation in bufGeometry

				var vertices = bufGeometry.attributes.position.array;
				var idxVertices = indexedBufferGeom.attributes.position.array;
				var indices = indexedBufferGeom.index.array;

				var numIdxVertices = idxVertices.length / 3;
				var numVertices = vertices.length / 3;

				bufGeometry.ammoVertices = idxVertices;
				bufGeometry.ammoIndices = indices;
				bufGeometry.ammoIndexAssociation = [];

				for ( var i = 0; i < numIdxVertices; i ++ ) {

					var association = [];
					bufGeometry.ammoIndexAssociation.push( association );

					var i3 = i * 3;

					for ( var j = 0; j < numVertices; j ++ ) {

						var j3 = j * 3;
						if ( isEqual( idxVertices[ i3 ], idxVertices[ i3 + 1 ], idxVertices[ i3 + 2 ],
							vertices[ j3 ], vertices[ j3 + 1 ], vertices[ j3 + 2 ] ) ) {

							association.push( j3 );

						}

					}

				}

			}

			function createSoftVolume( bufferGeom, mass, pressure ) {

				processGeometry( bufferGeom );

				var volume = new THREE.Mesh( bufferGeom, new THREE.MeshPhongMaterial( { color: 0xFFFFFF } ) );
				volume.castShadow = true;
				volume.receiveShadow = true;
				volume.frustumCulled = false;
				scene.add( volume );

				textureLoader.load( "textures/colors.png", function ( texture ) {

					volume.material.map = texture;
					volume.material.needsUpdate = true;

				} );

				// Volume physic object

				var volumeSoftBody = softBodyHelpers.CreateFromTriMesh(
					physicsWorld.getWorldInfo(),
					bufferGeom.ammoVertices,
					bufferGeom.ammoIndices,
					bufferGeom.ammoIndices.length / 3,
					true );

				var sbConfig = volumeSoftBody.get_m_cfg();
				sbConfig.set_viterations( 40 );
				sbConfig.set_piterations( 40 );

				// Soft-soft and soft-rigid collisions
				sbConfig.set_collisions( 0x11 );

				// Friction
				sbConfig.set_kDF( 0.1 );
				// Damping
				sbConfig.set_kDP( 0.01 );
				// Pressure
				sbConfig.set_kPR( pressure );
				// Stiffness
				volumeSoftBody.get_m_materials().at( 0 ).set_m_kLST( 0.9 );
				volumeSoftBody.get_m_materials().at( 0 ).set_m_kAST( 0.9 );

				volumeSoftBody.setTotalMass( mass, false );
				Ammo.castObject( volumeSoftBody, Ammo.btCollisionObject ).getCollisionShape().setMargin( margin );
				physicsWorld.addSoftBody( volumeSoftBody, 1, - 1 );
				volume.userData.physicsBody = volumeSoftBody;
				// Disable deactivation
				volumeSoftBody.setActivationState( 4 );

				softBodies.push( volume );

			}

			function createParalellepiped( sx, sy, sz, mass, pos, quat, material, rs=true, cs=false ) {

				var threeObject = new THREE.Mesh( new THREE.BoxBufferGeometry( sx, sy, sz, 1, 1, 1 ), material );
				var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx * 0.5, sy * 0.5, sz * 0.5 ) );
				shape.setMargin( margin );

				var hitbox = createRigidBody( threeObject, shape, mass, pos, quat );
                hitbox.setFriction( 1 );

                threeObject.receiveShadow = rs;
                threeObject.castShadow = cs;

				return threeObject;

			}

			function mkShape( mass, pos, quat, material, points, rs=true, cs=false ) {

                var shape_geometry = new THREE.Shape();

                var i = false;
                var lowx, highx, lowz, highz;
                points.forEach((vert) => {
                    if(!i) { shape_geometry.moveTo( vert[0], vert[1] ); i=true; lowx = vert[0]; lowz = vert[1]; highx = vert[0]; highz = vert[1]; return; }
                    shape_geometry.lineTo( vert[0], vert[1] );
                    if(vert[0]<lowx) { lowx = vert[0]; }
                    if(vert[1]<lowz) { lowz = vert[1]; }
                    if(vert[0]>highx) { highx = vert[0]; }
                    if(vert[1]>highz) { highz = vert[1]; }
                });
                var sx = highx-lowx;
                var sz = 0.01;
                var sy = highz-lowz;

                var extrudeSettings = { depth: 0.2, bevelEnabled: true, bevelSegments: 4, steps: 4, bevelSize: 0.2, bevelThickness: 0.2 };

                var gg = new THREE.ExtrudeBufferGeometry( shape_geometry, extrudeSettings );

                material.dithering = true;
                var shape_mesh = new THREE.Mesh( gg, material );
                shape_mesh.receiveShadow = rs;
                shape_mesh.castShadow = cs;


				var shape = new Ammo.btBoxShape( new Ammo.btVector3( sx, sy, sz ) );
				shape.setMargin( margin );

				createRigidBody( shape_mesh, shape, mass, pos, quat );

                // shape_mesh.rotation.x = Math.PI/2;

				return shape_mesh;

			}

			function createRigidBody( threeObject, physicsShape, mass, pos, quat ) {

				threeObject.position.copy( pos );
				threeObject.quaternion.copy( quat );

				var transform = new Ammo.btTransform();
				transform.setIdentity();
				transform.setOrigin( new Ammo.btVector3( pos.x, pos.y, pos.z ) );
				transform.setRotation( new Ammo.btQuaternion( quat.x, quat.y, quat.z, quat.w ) );
				var motionState = new Ammo.btDefaultMotionState( transform );

				var localInertia = new Ammo.btVector3( 0, 0, 0 );
				physicsShape.calculateLocalInertia( mass, localInertia );

				var rbInfo = new Ammo.btRigidBodyConstructionInfo( mass, motionState, physicsShape, localInertia );
				var body = new Ammo.btRigidBody( rbInfo );

				threeObject.userData.physicsBody = body;

				// scene.add( threeObject );

				if ( mass > 0 ) {

					rigidBodies.push( threeObject );

					// Disable deactivation
					body.setActivationState( 4 );

				}

				physicsWorld.addRigidBody( body );

				return body;

			}

			function createRigidBodyGroup( threeObject, physicsShape, mass, pos, quat ) {

				threeObject.position.copy( pos );
				threeObject.quaternion.copy( quat );

				var transform = new Ammo.btTransform();
				transform.setIdentity();
				transform.setOrigin( new Ammo.btVector3( pos.x, pos.y, pos.z ) );
				transform.setRotation( new Ammo.btQuaternion( quat.x, quat.y, quat.z, quat.w ) );
				var motionState = new Ammo.btDefaultMotionState( transform );

				var localInertia = new Ammo.btVector3( 0, 0, 0 );
				physicsShape.calculateLocalInertia( mass, localInertia );

				var rbInfo = new Ammo.btRigidBodyConstructionInfo( mass, motionState, physicsShape, localInertia );
				var body = new Ammo.btRigidBody( rbInfo );

				threeObject.userData.physicsBody = body;

				scene.add( threeObject );

				if ( mass > 0 ) {

					rigidBodies.push( threeObject );

					// Disable deactivation
					body.setActivationState( 4 );

				}

				physicsWorld.addRigidBody( body );

				return body;

			}

			function initInput() {

				// window.addEventListener( 'mousedown', function ( event ) {
                //
				// 	if ( ! clickRequest ) {
                //
						// mouseCoords.set(
						// 	( event.clientX / window.innerWidth ) * 2 - 1,
						// 	- ( event.clientY / window.innerHeight ) * 2 + 1
						// );
                //
				// 		clickRequest = true;
                //
				// 	}
                //
				// }, false );

			}

			function processClick() {

				if ( clickRequest ) {

					rayCaster.setFromCamera( mouseCoords, camera );

					// Creates a ball
					var ballMass = 300;
					var ballRadius = 400;

					var ball = new THREE.Mesh( new THREE.SphereBufferGeometry( ballRadius, 10, 10 ), setMtls(woodTextures[1], 0.5, 0.5) );
					ball.castShadow = true;
					ball.receiveShadow = true;
					var ballShape = new Ammo.btSphereShape( ballRadius );
					ballShape.setMargin( margin );
					pos.copy( rayCaster.ray.direction );
					pos.add( rayCaster.ray.origin );
					quat.set( 0, 0, 0, 1 );
					var ballBody = createRigidBody( ball, ballShape, ballMass, pos, quat );
					ballBody.setFriction( 1 );

					pos.copy( rayCaster.ray.direction );
					pos.multiplyScalar( 1400 );
					ballBody.setLinearVelocity( new Ammo.btVector3( pos.x, pos.y, pos.z ) );

					clickRequest = false;

				}

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

			}

            var last_day;
            var last_date_frmt;
            var last_market_status;
            var last_accountBalance;
            var last_salesBalance;
            var last_time;
            var last_stall_count;
            var last_customer_count = 0;
            var stall_count;
            var customer_count;
            var custEnterMarketChc, custLeaveMarketChc;

			function animate() {

				requestAnimationFrame( animate );

				render();
				stats.update();
                TWEEN.update();
                controls.update();

                //---------------------------------------------------------------
                // Game timekeeping
                saveFile.stats.gameTime++;

                let unix_timestamp = 946706400+(saveFile.stats.gameTime*gameTimeUnit);
                var date = new Date(unix_timestamp * 1000);

                var days = [
                  'Sunday',
                  'Monday',
                  'Tuesday',
                  'Wednesday',
                  'Thursday',
                  'Friday',
                  'Saturday'
                ];

                var day = days[date.getDay()];
                var date_frmt = date.getDate()+"/"+(date.getMonth()+1)+"/"+date.getFullYear();
                var hours = date.getHours();
                var m = 'am';
                if(date.getHours()>11) {
                    m = 'pm';
                }
                if(date.getHours()>12) {
                    hours = date.getHours()-12;
                    m = 'pm';
                }
                if(hours == 0) {
                    hours = "00";
                }
                var time = hours+":"+("0"+date.getMinutes()).substr(-2)+" "+m;

                // market is open between 6am and midday
                market_status = 'closed';
                if(date.getHours()>5 && date.getHours()<12) {
                    if(!$(".market-ticker-container").hasClass("bg-success")) {
                        $(".market-ticker-container").addClass("bg-success").removeClass("bg-warning");
                        $(".date-ticker").removeClass("text-black-50").addClass("text-white-50");
                        $(".time-ticker").removeClass("text-black-50").addClass("text-white-50");
                    }
                    market_status = 'open';
                    if(saveFile.stats.gameTime % 300 == 0) {
                        // market revenue formula
                        if(savedStalls) {

                            scene.getObjectByName("stalls_group").children.forEach((st) => {
                                if(st.info.status=="open") {
                                    if(Math.random()<(st.client.sc._ca/savedStalls.length/100)*marketCustomers.length) {
                                        if(Math.random()<(st.client.sc._cs/100)) {
                                            var _sale = rndArray(st.client._p)._pr;
                                            st.info.sales+=parseFloat(_sale);
                                            st.info.cust++;
                                            saveFile.stats.salesBalance = parseFloat(saveFile.stats.salesBalance)+(parseFloat(_sale));
                                        }
                                    }
                                }
                            });




                        }
                    }
                    if(lastSave!=false) {
                        // save game if market is open and last save was over 20s ago (400 frames @ 20fps)
                        // console.log(lastSave);
                        if((saveFile.stats.gameTime-lastSave)>400) {
                            lastSave = false;
                            save_game_to_server();
                        }
                    }

                    if(savedStalls && saveFile.stats.gameTime % 2 == 0) {

                        switch(date.getHours()) {
                            case 6:
                            custEnterMarketChc = cust_enter_market_chc*1.03;
                            custLeaveMarketChc = cust_leave_market_chc*0.1;
                            break;
                            case 7:
                            custEnterMarketChc = cust_enter_market_chc*1.02;
                            custLeaveMarketChc = cust_leave_market_chc*0.2;
                            break;
                            case 8:
                            custEnterMarketChc = cust_enter_market_chc*1.02;
                            custLeaveMarketChc = cust_leave_market_chc*0.5;
                            break;
                            case 9:
                            custEnterMarketChc = cust_enter_market_chc*1.01;
                            custLeaveMarketChc = cust_leave_market_chc*0.8;
                            break;
                            case 10:
                            custEnterMarketChc = cust_enter_market_chc*1.00;
                            custLeaveMarketChc = cust_leave_market_chc*0.90;
                            break;
                            case 11:
                            custEnterMarketChc = cust_enter_market_chc*0.8;
                            custLeaveMarketChc = cust_leave_market_chc*1.00;
                            break;
                        }

                        // trigger customers enter market
                        if(Math.random()<=custEnterMarketChc) {
                            marketCustomers.push( {} );
                        }

                        // trigger customers leave market
                        if(Math.random()<=custLeaveMarketChc) {
                            if(marketCustomers.length!=0) { marketCustomers.shift(); }
                        }

                    }

                } else {
                    if(!$(".market-ticker-container").hasClass("bg-warning")) {
                        $(".date-ticker").addClass("text-black-50").removeClass("text-white-50");
                        $(".time-ticker").addClass("text-black-50").removeClass("text-white-50");
                        $(".market-ticker-container").addClass("bg-warning").removeClass("bg-success");
                    }
                    // increase time passing if market is closed
                    gameTimeMultiplier = 30;
                    // increase time passing further if sundown
                    if(isSundown) { gameTimeMultiplier = 70; }
                    saveFile.stats.gameTime+=(gameTimeMultiplier);
                }

                // trigger sunrise at 4:00am game time
                if(date.getHours()==4 && isTriggerSunrise) {
                    triggerSunrise();
                    isTriggerSunrise = false;
                    isTriggerSunset = true;
                    lightpoles_group.children.forEach((lp) => {
                        lp.turnOff.start();
                    });
                }

                // trigger sunset at 4:00pm game time
                if(date.getHours()==16 && isTriggerSunset) {
                    triggerSunset();
                    isTriggerSunrise = true;
                    isTriggerSunset = false;
                    setTimeout(function(){ light.castShadow = false; }, 9000 );
                    lightpoles_group.children.forEach((lp) => {
                        lp.turnOn.delay(9000);
                        lp.turnOn.start();
                    });
                }

                // trigger customers emptying at 1:00pm game time
                if(date.getHours()==12 && date.getMinutes()>20) {
                    if(marketCustomers.length!=0) { marketCustomers.shift(); }
                }

                //Update tickers
    			function updateTickers() {

                    // do as little as possible - only update the HTML if they change

                    // account balance ticker
                    if(last_accountBalance!=saveFile.stats.accountBalance) { $(".account-balance-ticker").html("$ "+_fcur(saveFile.stats.accountBalance)); }
    				if(last_salesBalance!=saveFile.stats.salesBalance) { $(".sales-balance-ticker").html("$ "+_fcur(saveFile.stats.salesBalance)); }

                    if(last_day!=day) { $(".ticker-container .day-ticker").html(day); }
                    if(last_date_frmt!=date_frmt) { $(".ticker-container .date-ticker").html(date_frmt); }
                    if(last_market_status!=market_status) { $(".ticker-container .market-status-ticker").html(market_status); }
                    if(last_time!=time) { $(".ticker-container .time-ticker").html(time); }

                    if(savedStalls) {
                        // stall count ticker
                        stall_count = savedStalls.length;
                        if(last_stall_count!=stall_count) {
                            var s = "s";
                            if(stall_count==1) { s=""; }
                            $(".stall-count-ticker").html(stall_count);
                            $(".stall-word-ticker").html("stall"+s);
                        }
                    }

                    if(marketCustomers!=undefined) {
                        // customer count ticker
                        customer_count = marketCustomers.length;
                        if(last_customer_count!==customer_count) {
                            var s = "s";
                            if(customer_count==1) { s=""; }
                            $(".customer-count-ticker").html(customer_count);
                            $(".customer-word-ticker").html("customer"+s);
                        }
                    }

                    last_day = day;
                    last_date_frmt = date_frmt;
                    last_market_status = market_status;
                    last_accountBalance = saveFile.stats.accountBalance;
                    last_salesBalance = saveFile.stats.salesBalance;
                    last_time = time;
                    last_stall_count = stall_count;
                    last_customer_count = customer_count;

    			}

                function animateWindmill(n) {
                    //windmill
                    scene.getObjectByName('windmillBlades').rotation.z += (Math.sin(saveFile.stats.gameTime/200)/(100/n));
                    scene.getObjectByName('windmillVertical').rotation.y += (Math.sin(saveFile.stats.gameTime/700)/(300/n));
                    scene.getObjectByName('windmillBlades').rotation.needsUpdate = true;
                    scene.getObjectByName('windmillVertical').rotation.needsUpdate = true;
                }


                if(saveFile.stats.gameTime % 3 == 0) {
                    updateTickers();
                }

                if(saveFile.stats.gameTime % 3 == 0) {
                    animateWindmill(3);
                }



			}

			function render() {

				var deltaTime = clock.getDelta();

				updatePhysics( deltaTime );

				processClick();

				renderer.render( scene, camera );

			}

			function updatePhysics( deltaTime ) {

				// Step world
				physicsWorld.stepSimulation( deltaTime, 10 );

				// Update soft volumes
				for ( var i = 0, il = softBodies.length; i < il; i ++ ) {

					var volume = softBodies[ i ];
					var geometry = volume.geometry;
					var softBody = volume.userData.physicsBody;
					var volumePositions = geometry.attributes.position.array;
					var volumeNormals = geometry.attributes.normal.array;
					var association = geometry.ammoIndexAssociation;
					var numVerts = association.length;
					var nodes = softBody.get_m_nodes();
					for ( var j = 0; j < numVerts; j ++ ) {

						var node = nodes.at( j );
						var nodePos = node.get_m_x();
						var x = nodePos.x();
						var y = nodePos.y();
						var z = nodePos.z();
						var nodeNormal = node.get_m_n();
						var nx = nodeNormal.x();
						var ny = nodeNormal.y();
						var nz = nodeNormal.z();

						var assocVertex = association[ j ];

						for ( var k = 0, kl = assocVertex.length; k < kl; k ++ ) {

							var indexVertex = assocVertex[ k ];
							volumePositions[ indexVertex ] = x;
							volumeNormals[ indexVertex ] = nx;
							indexVertex ++;
							volumePositions[ indexVertex ] = y;
							volumeNormals[ indexVertex ] = ny;
							indexVertex ++;
							volumePositions[ indexVertex ] = z;
							volumeNormals[ indexVertex ] = nz;

						}

					}

					geometry.attributes.position.needsUpdate = true;
					geometry.attributes.normal.needsUpdate = true;

				}

				// Update rigid bodies
				for ( var i = 0, il = rigidBodies.length; i < il; i ++ ) {

					var objThree = rigidBodies[ i ];
					var objPhys = objThree.userData.physicsBody;
					var ms = objPhys.getMotionState();
					if ( ms ) {

						ms.getWorldTransform( transformAux1 );
						var p = transformAux1.getOrigin();
						var q = transformAux1.getRotation();
						objThree.position.set( p.x(), p.y(), p.z() );
						objThree.quaternion.set( q.x(), q.y(), q.z(), q.w() );

					}

				}

			}

		</script>
	</body>
</html>