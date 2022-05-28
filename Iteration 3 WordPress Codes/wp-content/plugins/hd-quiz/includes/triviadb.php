<?php
/*
    HDQuiz - Trivia DB Integration
*/


if (!current_user_can('edit_others_pages')) {
    die();
}

wp_enqueue_style(
    'hdq_admin_style',
    plugin_dir_url(__FILE__) . 'css/hdq_admin.css?v=' . HDQ_PLUGIN_VERSION
);

wp_enqueue_script(
    'hdq_admin_script',
    plugins_url('/js/hdq_admin.js?v=' . HDQ_PLUGIN_VERSION, __FILE__),
    array('jquery', 'jquery-ui-draggable'),
    HDQ_PLUGIN_VERSION,
    true
);

?>

<div id="main" style="max-width: 800px; background: #f3f3f3; border: 1px solid #ddd; margin-top: 2rem">
    <div id="header">
        <h1 id="heading_title" style="margin-top:0">
            HD Quiz - Instant Quiz Builder Packs
        </h1>
    </div>


    <p>HD Quiz has partnered with the <a href="https://triviadatabase.com/product-category/hdquiz/?utm_source=HDQuiz_triviadb" target="_blank">Trivia Company</a> to make it easier than ever to create fun quizzes. With their <em>massive</em> repository containing hundreds of thousands of questions, for a small fee, you can own a pack of questions and use them with HD Quiz.</p>
    <p>
        The Trivia Company has worked with the largest companies in the world including Google, Sony, and the popular QuizUp app to provide question packs.
    </p>


    <div class="hdq_highlight">
        <p>
            Please note that this is a partnership between HD Quiz and the Trivia Company to allow the purchase of premade question packs.
        </p>
        <p>
            <strong>If you have problems</strong> with the questions themselves, please contact the <a href="https://triviadatabase.com/contact-us/" target="_blank">Trivia Company</a> for support. If you have trouble importing the questions, please <a href="https://harmonicdesign.ca/hd-quiz" target="_blank">contact me here</a>.
        </p>
        <p>
            <small>Also please note that I have zero control over the pricing of any of these packs. These packs are created, sold, and distributed by the Trivia Company. I also strongly recommend getting their free pack to test everything out first!</small>
        </p>
    </div>


    <div style="margin: 1em auto; width: max-content;">


        <div class="cont">
            <a href="https://triviadatabase.com/product/seinfeld-tv-trivia-hd-quiz-builder-pack-free-sample/?utm_source=HDQuiz_triviadb" target="_blank" class="button_blob">
                <div class="blob">
                    <svg xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 310 350">
                        <path d="M156.4,339.5c31.8-2.5,59.4-26.8,80.2-48.5c28.3-29.5,40.5-47,56.1-85.1c14-34.3,20.7-75.6,2.3-111  c-18.1-34.8-55.7-58-90.4-72.3c-11.7-4.8-24.1-8.8-36.8-11.5l-0.9-0.9l-0.6,0.6c-27.7-5.8-56.6-6-82.4,3c-38.8,13.6-64,48.8-66.8,90.3c-3,43.9,17.8,88.3,33.7,128.8c5.3,13.5,10.4,27.1,14.9,40.9C77.5,309.9,111,343,156.4,339.5z" />
                    </svg>
                </div>
                <div class="text">
                    <p>FREE</p>
                    <p style="font-size: 0.8em">download Seinfeld quiz pack</p>
                </div>
            </a>
        </div>

        <style>
            .cont {
                position: relative;
                overflow: hidden;
                border-radius: 10px;
                border: 1px solid #9899;
                display: inline-block;
            }

            .button_blob {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                background-color: #66cbff;
                position: relative;
                border-radius: 10px;
                outline: none;
                transition: box-shadow 0.3s linear;
                border: 2px solid transparent
            }

            .button_blob:hover {
                border: 2px solid #000
            }

            .blob {
                position: relative;
                top: 10%;
                left: 0%;
                fill: #f9f8e0;
                transform-origin: 50% 50%;
                filter: drop-shadow(4px 4px 4px #222);
                animation: bump 5.5s ease infinite;
                width: 200px;
                height: 100px;
            }

            @keyframes bump {
                0% {
                    transform: scale(1, 1) translateX(0px);
                }

                9% {
                    transform: scale(1.1, 0.9) translateY(20px);
                }

                28% {
                    transform: scale(0.7, 1.3) translateY(-100px) translateX(55px);
                }

                35% {
                    transform: scale(1, 1) translateY(20px);
                }

                50% {
                    transform: scale(1, 1.1);
                }

                60% {
                    transform: scale(1.1, 0.9) translateY(20px);
                }

                80% {
                    transform: scale(0.7, 1.3) translateY(-100px) translateX(-55px);
                }

                88% {
                    transform: scale(1, 1) translateY(20px);
                }

                100% {
                    transform: scale(1, 1) translateX(0px);
                }
            }

            .text {
                display: grid;
                place-items: center;
                position: absolute;
                padding: 0 1em;
                text-align: center;
            }

            #main .text p {
                margin: 0;
                font-size: 26px;
                color: #000
            }
        </style>
    </div>


    <h2>
        Getting Started
    </h2>
    <p>
        <strong>Before purchasing a pack</strong> from the Trivia Company, please verify that your site is able to import the example CSV file. This is to ensure that your server and site are compatible with the import.
    </p>
    <a href="<?php echo get_admin_url(null, "?page=hdq_tools_csv_importer"); ?>" class="hdq_button2" role="button" title="run the import tool">
        RUN IMPORT TOOL
    </a>
    <a href="https://triviadatabase.com/product-category/hdquiz/?utm_source=HDQuiz_triviadb" target="_blank" class="hdq_button" role="button" title="view availalbe packs for purchase">
        PURCHASE PACKS
    </a>

    <p>
        Once a pack has been purchased, you will receive the questions as a .CSV fille preformatted for HD Quiz. Once you have the CSV, head over to HD Quiz's import tool and import the CSV.
    </p>
</div>