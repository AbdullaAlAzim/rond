<?php

defined( 'ABSPATH' ) || exit;

global $post;
		
$configurator_title = get_post_meta( $post->ID, 'configurator_title', true );
$configurator_description = get_post_meta( $post->ID, 'configurator_description', true );
$configurator_default_gallery = get_post_meta( $post->ID, 'configurator_default-gallery', true );
$configurator_default_accessories = get_post_meta( $post->ID, 'configurator_default_accessories', true );
$configurator_options = get_post_meta( $post->ID, 'configurator_options', true );
$configurator_mappings = get_post_meta( $post->ID, 'configurator_mappings', true );

// var_dump($configurator_mappings);
// var_dump($configurator_description);
// var_dump($configurator_default_gallery);
// var_dump($configurator_default_accessories);
?>

<section class="pt-10 pb-25">
    <div class="container 2xl:max-w-screen-3xl 2xl:mx-auto 2xl:px-25">
        <div class="configurator flex items-start" data-mappings='<?php echo json_encode( $configurator_mappings ); ?>' data-id="<?php echo $post->ID; ?>" data-default-accessories='<?php echo json_encode( $configurator_default_accessories ); ?>'>
            <div class="configurator__slider w-full hidden bg-gray-lighter lg:block lg:min-w-0 lg:sticky lg:top-25 lg:max-h-[calc(100vh_-_64px_-_74px)]"> <!-- lg:h-[calc(100vh_-_64px_-_74px)] lg:h-[53vw] lg:max-h-[calc(100vh_-_64px_-_74px)] -->

            <?php 
                foreach ( $configurator_options as $key => $conf_option ): 
                $image_ids = explode( ',', $conf_option["gallery"] );

                $options_key = str_replace( " ", "_", $conf_option["title"] );
                $options_key = str_replace( ".", "", $options_key );
                $options_key = strtolower( $options_key );
            ?>

                <div class="configurator__slider__inner<?php echo $key === array_key_first( $configurator_options ) ? ' configurator__slider__inner--active' : ''; ?>" data-name="<?php echo $options_key; ?>">
                    <div class="glide">
                        <div class="glide__track" data-glide-el="track">

                            <ul class="glide__slides">
                                <?php if ( $conf_option["gallery"] ): ?>
                                    <?php foreach( $image_ids as  $image_id): ?>
                                        <li class="glide__slide">
                                            <img src="<?php echo wp_get_attachment_image_url( $image_id, 'full', false ); ?>" class="w-full h-full object-cover lg:max-h-[calc(100vh_-_64px_-_74px)]"/>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                            
                        </div>
                        <?php if ( $conf_option["gallery"] ): ?>
                            <div class="glide__bullets glide__bullets--configurator<?php if ( count( $image_ids ) < 2 ) echo ' glide__bullets--configurator--disabled'; ?>" data-glide-el="controls[nav]">
                                <?php foreach( $image_ids as $key => $image_id): ?>
                                    <button class="glide__bullet" data-glide-dir="=<?php echo $key; ?>"></button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div data-glide-el="controls" class="carousel-controls carousel-controls--configurator<?php if ( count( $image_ids ) < 2 ) echo ' carousel-controls--configurator--disabled'; ?>">
                            <button data-glide-dir="<" onclick="event.preventDefault()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                                    <path opacity="0.6" d="M10.5039 1L1.50391 10L10.5039 19" stroke="#231F1E" stroke-width="2"/>
                                </svg>
                            </button>
                            <button data-glide-dir=">" onclick="event.preventDefault()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                                    <path opacity="0.6" d="M10.5039 1L1.50391 10L10.5039 19" stroke="#231F1E" stroke-width="2"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
            <?php endforeach; ?>
            </div>
            <div class="w-full lg:max-w-[28.0625rem] lg:pl-10 lg:box-content 2xl:pl-25">
                <div class="mb-12 lg:mb-28">
                    <a href="<?php echo get_permalink(); ?>" class="flex items-center gap-2 mb-8 text-black transition-colors hover:text-black/60 lg:mb-12">
                        <svg width="5" height="8" viewBox="0 0 5 8" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-0.5">
                            <path id="chevron_left" d="M4.21446 8L0 4L4.21446 0L5 0.745566L1.57108 4L5 7.25443L4.21446 8Z" fill="currentColor"/>
                        </svg>
                        <span class="label">
                            <?php _e( 'Back to product page', 'prado' ); ?>
                        </span>
                    </a>

                    <h1 class="heading mb-8 lg:heading--small"><?php echo $configurator_title; ?></h1>
                    <p class="text text-gray-dark"><?php echo $configurator_description; ?></p>
                </div>
                <form class="configurator__form">
                    <div class="flex flex-col gap-16 mb-16 lg:gap-28 2xl:gap-[12.5rem]">
                        <?php $index = 0; ?>
                        <?php foreach ( $configurator_options as $conf_option ): ?>
                            <?php 
                                // Create key from Title
                                $options_key = str_replace( " ", "_", $conf_option["title"] );
                                $options_key = str_replace( ".", "", $options_key );
                                $options_key = strtolower( $options_key );

                                // Create according-to-options array
                                foreach ( $conf_option['according_to_option'] as $key => $according_to ) {
                                    $image_ids = explode( ',', $according_to["gallery"] );
                                    $image_urls = array();

                                    foreach( $image_ids as  $image_id) {
                                        array_push( $image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                    }

                                    if ( count( $image_urls ) > 0 ) {
                                        $conf_option['according_to_option'][$key]["gallery"] = implode( ',', $image_urls);
                                    }
                                }

                                $gallery_image_ids = explode( ',', $conf_option["gallery"] );

                                $gallery_image_urls = array();

                                foreach( $gallery_image_ids as  $image_id) {
                                    array_push( $gallery_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                }

                                if ( count( $gallery_image_urls ) > 0 ) {
                                    $conf_option['gallery'] = implode( ',', $gallery_image_urls);
                                }

                            ?>
                            <div 
                                class="configurator__option flex flex-col gap-5<?php echo $index !== 0 ? " configurator__option--disabled" : ""; ?> <?php echo $conf_option['hide_by_default'] ? "configurator__option--removed" : ""; ?>"
                                data-real-gallery-according-to='<?php echo json_encode( $conf_option['real_gallery_according_to'] ); ?>'
                                data-gallery='<?php echo json_encode( $conf_option['gallery'] ); ?>'
                                data-according-to-options='<?php echo json_encode( $conf_option['according_to_option'] ); ?>'
                                data-use-layers="<?php echo $conf_option['use_layers']; ?>"
                                data-override-layer-images-for="<?php echo $conf_option["override_layer_images_for"]; ?>"
                                data-if-selected-remove-options='<?php echo json_encode( $conf_option["if_selected_remove_options"] ); ?>'
                                data-key="<?php echo $options_key; ?>"
                            >
                                <div>
                                    <h2 class="form-heading text-black"><?php echo $conf_option["conf_title"] ? $conf_option["conf_title"] : $conf_option["title"]; ?></h2>
                                    <div class="flex items-center gap-2">
                                        <span class="text text-gray-dark block"><?php echo $conf_option["description"]; ?></span>

                                        <?php if ( isset( $conf_option['popup']['type'] ) && $conf_option['popup']['type'] !== ""  && ( !isset( $conf_option['popup']['info_box_title'] ) || $conf_option['popup']['info_box_title'] === "" ) ): ?>
                                            <button class="configurator__option__popup-button transition-colors text-gray-dark hover:text-black">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <g clip-path="url(#clip0_5380_104123)">
                                                        <path d="M8 7.33333H8.66667V12H7.33333V8.66667C6.96533 8.66667 6.66667 8.368 6.66667 8C6.66667 7.632 6.96533 7.33333 7.33333 7.33333H8ZM8 16C3.582 16 0 12.418 0 8C0 3.582 3.582 0 8 0C12.418 0 16 3.582 16 8C16 12.418 12.418 16 8 16ZM8 14.6667C11.682 14.6667 14.6667 11.682 14.6667 8C14.6667 4.318 11.682 1.33333 8 1.33333C4.318 1.33333 1.33333 4.318 1.33333 8C1.33333 11.682 4.318 14.6667 8 14.6667ZM7 5C7 4.448 7.444 4 8 4C8.552 4 9 4.444 9 5C9 5.552 8.556 6 8 6C7.448 6 7 5.556 7 5Z" fill="currentColor"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_5380_104123">
                                                        <rect width="16" height="16" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php $image_ids = explode( ',', $conf_option["gallery"] ); ?>
                                
                                <div class="configurator__slider configurator__slider--mobile lg:hidden">
                                    <div class="glide">
                                        <div class="glide__track" data-glide-el="track">
                                            <ul class="glide__slides">
                                                <?php if ( $conf_option["gallery"] ): ?>
                                                    <?php foreach( $image_ids as  $image_id): ?>
                                                        <li class="glide__slide aspect-square">
                                                            <img src="<?php echo $image_id; ?>" class="w-full h-full object-cover"/>
                                                        </li>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <?php if ( $conf_option["gallery"] ): ?>
                                            <div class="glide__bullets glide__bullets--configurator<?php if ( count( $image_ids ) < 2 ) echo ' glide__bullets--configurator--disabled'; ?>" data-glide-el="controls[nav]">
                                                <?php foreach( $image_ids as $key => $image_id): ?>
                                                    <button class="glide__bullet" data-glide-dir="=<?php echo $key; ?>" onclick="event.preventDefault()"></button>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div data-glide-el="controls" class="carousel-controls carousel-controls--configurator<?php if ( count( $image_ids ) < 2 ) echo ' carousel-controls--configurator--disabled'; ?>">
                                            <button data-glide-dir="<" onclick="event.preventDefault()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                                                    <path opacity="0.6" d="M10.5039 1L1.50391 10L10.5039 19" stroke="#231F1E" stroke-width="2"/>
                                                </svg>
                                            </button>
                                            <button data-glide-dir=">" onclick="event.preventDefault()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="20" viewBox="0 0 12 20" fill="none">
                                                    <path opacity="0.6" d="M10.5039 1L1.50391 10L10.5039 19" stroke="#231F1E" stroke-width="2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <?php if ( $conf_option["type"] == "color" ): ?>    
                                    
                                    <div>
                                        <span class="block text mb-4">Select color</span>

                                        <div class="configurator__option__color-container">
                                            <div class="flex gap-4">
                                                <?php foreach ( $conf_option["options"] as $option ): ?>
                                                    <?php 
                                                        $option_key = str_replace( " ", "_", $option["title"] );
                                                        $option_key = strtolower( $option_key );
                                                    ?>
                                                    <?php $image_urls_strings = ''; ?>
                                                    <?php if ( $option["layer_gallery"] ): ?>
                                                            <?php 
                                                            
                                                            $image_ids = explode( ',', $option["layer_gallery"] );


                                                            $image_urls = array();

                                                            foreach( $image_ids as  $image_id) {
                                                                array_push( $image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                            }

                                                            if ( count( $image_urls ) > 0 ) {
                                                                $image_urls_strings = implode( ',', $image_urls);
                                                            }
                                                            
                                                    ?> 
                                                    <?php endif; ?>
                                                    <?php
                                                        // Create according-to-options array
                                                        foreach ( $option['according_to_option'] as $key => $according_to ) {
                                                            $acc_image_ids = explode( ',', $according_to["gallery"] );
                                                            $acc_image_urls = array();

                                                            foreach( $acc_image_ids as $image_id) {
                                                                array_push( $acc_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                            }

                                                            if ( count( $acc_image_urls ) > 0 ) {
                                                                $option['according_to_option'][$key]["gallery"] = implode( ',', $acc_image_urls);
                                                            }
                                                        }
                                                    ?>
                                                    <button 
                                                        class="configurator__option__button--color w-6 h-6 rounded-full border border-black/10"
                                                        style="background-color: <?php echo $option["option_color_code"]; ?>"
                                                        data-value="<?php echo $option_key; ?>" 
                                                        data-title="<?php echo $option["title"]; ?>"
                                                        data-layer-images="<?php echo $image_urls_strings; ?>"
                                                        data-accessories='<?php echo json_encode( $option["accessories"] ); ?>'
                                                        data-accessories-quantity="<?php echo $option["accessories_quantity"]; ?>"
                                                        data-accessories-bottom="<?php echo $option["accessories_bottom"]; ?>"
                                                        data-real-gallery-according-to='<?php echo json_encode( $conf_option['real_gallery_according_to'] ); ?>'
                                                        data-according-to-options='<?php echo json_encode( $option['according_to_option'] ); ?>'
                                                        data-if-selected-remove='<?php echo json_encode( $option['if_selected_remove'] ); ?>'
                                                        data-if-selected-enable='<?php echo json_encode( $option['if_selected_enable'] ); ?>'
                                                        data-if-selected-revove-default-values='<?php echo json_encode( $option['if_selected_remove_default_value'] ); ?>'
                                                        data-if-remove-values='<?php echo json_encode( $option["if_selected_remove_options_values"] ); ?>'
                                                        data-use-gallery-according-to-options="<?php echo $conf_option["use_gallery_according_to_for_options"]; ?>"
                                                    ></button>
                                                <?php endforeach; ?>
                                            </div>
                                            <span class="configurator__option__color-container__value" ></span>
                                        </div>

                                    </div>

                                <?php elseif ( $conf_option["type"] == "icon_blocks" ): ?>

                                    <div class="flex flex-col gap-4 lg:grid lg:grid-cols-2 ">
                                        <?php foreach ( $conf_option["options"] as $option ): ?>
                                            <?php 
                                                $option_key = str_replace( " ", "_", $option["title"] );
                                                $option_key = strtolower( $option_key );
                                            ?>
                                            <?php $image_urls_strings = ''; ?>
                                            <?php if ( $option["layer_gallery"] ): ?>
                                                <?php 
                                                
                                                $image_ids = explode( ',', $option["layer_gallery"] );
                                                $image_urls = array();

                                                foreach( $image_ids as  $image_id) {
                                                    array_push( $image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                }

                                                if ( count( $image_urls ) > 0 ) {
                                                    $image_urls_strings = implode( ',', $image_urls);
                                                }

                                                ?>        
                                            <?php endif; ?>
                                            <?php
                                                // Create according-to-options array
                                                foreach ( $option['according_to_option'] as $key => $according_to ) {
                                                    $acc_image_ids = explode( ',', $according_to["gallery"] );
                                                    $acc_image_urls = array();

                                                    foreach( $acc_image_ids as $image_id) {
                                                        array_push( $acc_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                    }

                                                    if ( count( $acc_image_urls ) > 0 ) {
                                                        $option['according_to_option'][$key]["gallery"] = implode( ',', $acc_image_urls);
                                                    }
                                                }
                                            ?>
                                            <button 
                                                class="configurator__option__button configurator__option__button--icon" 
                                                data-value="<?php echo $option_key; ?>" 
                                                data-layer-images="<?php echo $image_urls_strings; ?>" 
                                                data-real-gallery-according-to='<?php echo json_encode( $conf_option['real_gallery_according_to'] ); ?>'
                                                data-according-to-options='<?php echo json_encode( $option['according_to_option'] ); ?>'
                                                data-if-selected-remove='<?php echo json_encode( $option['if_selected_remove'] ); ?>'
                                                data-if-selected-enable='<?php echo json_encode( $option['if_selected_enable'] ); ?>'
                                                data-default="<?php echo $option["default"]; ?>"
                                                data-accessories='<?php echo json_encode( $option["accessories"] ); ?>'
                                                data-accessories-quantity="<?php echo $option["accessories_quantity"]; ?>"
                                                data-accessories-bottom="<?php echo $option["accessories_bottom"]; ?>"
                                                data-parent-key="<?php echo $options_key; ?>"
                                                data-if-remove-values='<?php echo json_encode( $option["if_selected_remove_options_values"] ); ?>'
                                                data-if-selected-remove-default-values='<?php echo json_encode( $option['if_selected_remove_default_value'] ); ?>'
                                                data-use-gallery-according-to-options="<?php echo $conf_option["use_gallery_according_to_for_options"]; ?>"
                                            >
                                                <?php if ( $option['icon_image'] ): ?>
                                                    <img src="<?php echo wp_get_attachment_image_url( $option['icon_image'], 'full', false ); ?>" />
                                                <?php endif; ?>
                                                <span class="text text-black"><?php echo $option["title"]; ?></span>
                                            </button>
                                            <?php if ( is_array( $option['suboptions'] ) && count( $option['suboptions'] ) > 0 ): ?>
                                                <div class="configurator__option__suboptions mb-6">
                                                    <span class="block text mb-4">Select color</span>
                                                    <div class="configurator__option__color-container">
                                                        <div class="flex gap-4">
                                                            <?php foreach ( $option['suboptions'] as $suboption ): ?>
                                                                <?php 
                                                                    $suboption_key = str_replace( " ", "_", $suboption["title"] );
                                                                    $suboption_key = strtolower( $suboption_key );                                                                    
                                                                ?>
                                                                <?php 
                                                                    $suboption_image_urls_strings = '';
                                                                    if ( $suboption["layer_gallery"] ) {
                                                                        $suboption_image_ids = explode( ',', $suboption["layer_gallery"] );
                                                                        $suboption_image_urls = array();
                    
                                                                        foreach( $suboption_image_ids as  $image_id) {
                                                                            array_push( $suboption_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                                        }
                    
                                                                        if ( count( $suboption_image_urls ) > 0 ) {
                                                                            $suboption_image_urls_strings = implode( ',', $suboption_image_urls);
                                                                        }
                                                                    }
                                                                ?>

                                                                <?php
                                                                    // Create according-to-options array
                                                                    foreach ( $suboption['according_to_option'] as $key => $according_to ) {
                                                                        $acc_image_ids = explode( ',', $according_to["gallery"] );
                                                                        $acc_image_urls = array();

                                                                        foreach( $acc_image_ids as $image_id) {
                                                                            array_push( $acc_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                                        }

                                                                        if ( count( $acc_image_urls ) > 0 ) {
                                                                            $suboption['according_to_option'][$key]["gallery"] = implode( ',', $acc_image_urls);
                                                                        }
                                                                    }
                                                                ?>

                                                                <button 
                                                                    class="configurator__option__button--color configurator__option__button__suboption w-6 h-6 rounded-full border border-black/10"
                                                                    style="<?php echo $suboption["color_image"] ? 'background-image: url(' . wp_get_attachment_image_url( $suboption["color_image"], 'full', false ) . ')' : 'background-color: ' . $suboption["color_code"]; ?>" 
                                                                    data-value="<?php echo $option_key; ?>_-_<?php echo $suboption_key; ?>"
                                                                    data-title="<?php echo $suboption["title"]; ?>" 
                                                                    data-suboption="true"
                                                                    data-layer-images="<?php echo $suboption_image_urls_strings; ?>" 
                                                                    data-according-to-options='<?php echo json_encode( $suboption['according_to_option'] ); ?>'
                                                                    data-real-gallery-according-to='<?php echo json_encode( $conf_option['real_gallery_according_to'] ); ?>'
                                                                    data-use-gallery-according-to-options="<?php echo $conf_option["use_gallery_according_to_for_options"]; ?>"
                                                                >
                                                                </button>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <span class="configurator__option__color-container__value" ></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>

                                <?php else: ?>

                                    <div class="flex flex-col gap-4">
                                        <?php foreach ( $conf_option["options"] as $option ): ?>
                                            <?php 
                                                $option_key = str_replace( " ", "_", $option["title"] );
                                                $option_key = strtolower( $option_key );
                                            ?>
                                            <?php $image_urls_strings = ''; ?>
                                            <?php if ( $option["layer_gallery"] ): ?>
                                                <?php 
                                                
                                                $image_ids = explode( ',', $option["layer_gallery"] );
                                                $image_urls = array();

                                                foreach( $image_ids as  $image_id) {
                                                    array_push( $image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                }

                                                if ( count( $image_urls ) > 0 ) {
                                                    $image_urls_strings = implode( ',', $image_urls);
                                                }

                                                ?>        
                                            <?php endif; ?>
                                            <?php
                                                // Create according-to-options array
                                                foreach ( $option['according_to_option'] as $key => $according_to ) {
                                                    $acc_image_ids = explode( ',', $according_to["gallery"] );
                                                    $acc_image_urls = array();

                                                    foreach( $acc_image_ids as $image_id) {
                                                        array_push( $acc_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                    }

                                                    if ( count( $acc_image_urls ) > 0 ) {
                                                        $option['according_to_option'][$key]["gallery"] = implode( ',', $acc_image_urls);
                                                    }
                                                }
                                            ?>
                                            <button 
                                                class="configurator__option__button" 
                                                data-value="<?php echo $option_key; ?>" 
                                                data-layer-images="<?php echo $image_urls_strings; ?>" 
                                                data-real-gallery-according-to='<?php echo json_encode( $conf_option['real_gallery_according_to'] ); ?>'
                                                data-according-to-options='<?php echo json_encode( $option['according_to_option'] ); ?>'
                                                data-if-selected-remove='<?php echo json_encode( $option['if_selected_remove'] ); ?>'
                                                data-if-selected-enable='<?php echo json_encode( $option['if_selected_enable'] ); ?>'
                                                data-default="<?php echo $option["default"]; ?>"
                                                data-accessories='<?php echo json_encode( $option["accessories"] ); ?>'
                                                data-accessories-quantity="<?php echo $option["accessories_quantity"]; ?>"
                                                data-accessories-bottom="<?php echo $option["accessories_bottom"]; ?>"
                                                data-parent-key="<?php echo $options_key; ?>"
                                                data-if-remove-values='<?php echo json_encode( $option["if_selected_remove_options_values"] ); ?>'
                                                data-if-selected-remove-default-values='<?php echo json_encode( $option['if_selected_remove_default_value'] ); ?>'
                                                data-use-gallery-according-to-options="<?php echo $conf_option["use_gallery_according_to_for_options"]; ?>"
                                            >
                                                <span class="text text-black"><?php echo $option["title"]; ?></span>
                                                <span class="label text-gray-dark leading-4 text-left"><?php echo $option["description"]; ?></span>
                                            </button>
                                            <?php if ( is_array( $option['suboptions'] ) && count( $option['suboptions'] ) > 0 ): ?>
                                                <div class="configurator__option__suboptions mb-6">
                                                    <span class="block text mb-4">Select color</span>
                                                    <div class="configurator__option__color-container">
                                                        <div class="flex gap-4">
                                                            <?php foreach ( $option['suboptions'] as $suboption ): ?>
                                                                <?php 
                                                                    $suboption_key = str_replace( " ", "_", $suboption["title"] );
                                                                    $suboption_key = strtolower( $suboption_key );                                                                    
                                                                ?>
                                                                <?php 
                                                                    $suboption_image_urls_strings = '';
                                                                    if ( $suboption["layer_gallery"] ) {
                                                                        $suboption_image_ids = explode( ',', $suboption["layer_gallery"] );
                                                                        $suboption_image_urls = array();
                    
                                                                        foreach( $suboption_image_ids as  $image_id) {
                                                                            array_push( $suboption_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                                        }
                    
                                                                        if ( count( $suboption_image_urls ) > 0 ) {
                                                                            $suboption_image_urls_strings = implode( ',', $suboption_image_urls);
                                                                        }
                                                                    }
                                                                ?>

                                                                <?php
                                                                    // Create according-to-options array
                                                                    foreach ( $suboption['according_to_option'] as $key => $according_to ) {
                                                                        $acc_image_ids = explode( ',', $according_to["gallery"] );
                                                                        $acc_image_urls = array();

                                                                        foreach( $acc_image_ids as $image_id) {
                                                                            array_push( $acc_image_urls, wp_get_attachment_image_url( $image_id, 'full', false ));
                                                                        }

                                                                        if ( count( $acc_image_urls ) > 0 ) {
                                                                            $suboption['according_to_option'][$key]["gallery"] = implode( ',', $acc_image_urls);
                                                                        }
                                                                    }
                                                                ?>

                                                                <button 
                                                                    class="configurator__option__button--color configurator__option__button__suboption w-6 h-6 rounded-full border border-black/10"
                                                                    style="<?php echo $suboption["color_image"] ? 'background-image: url(' . wp_get_attachment_image_url( $suboption["color_image"], 'full', false ) . ')' : 'background-color: ' . $suboption["color_code"]; ?>" 
                                                                    data-value="<?php echo $option_key; ?>_-_<?php echo $suboption_key; ?>"
                                                                    data-title="<?php echo $suboption["title"]; ?>" 
                                                                    data-suboption="true"
                                                                    data-accessories='<?php echo json_encode( $suboption["accessories"] ); ?>'
                                                                    data-accessories-quantity="<?php echo $suboption["accessories_quantity"]; ?>"
                                                                    data-accessories-bottom="<?php echo $suboption["accessories_bottom"]; ?>"
                                                                    data-parent-key="<?php echo $options_key; ?>"
                                                                    data-layer-images="<?php echo $suboption_image_urls_strings; ?>" 
                                                                    data-according-to-options='<?php echo json_encode( $suboption['according_to_option'] ); ?>'
                                                                    data-real-gallery-according-to='<?php echo json_encode( $conf_option['real_gallery_according_to'] ); ?>'
                                                                    data-use-gallery-according-to-options="<?php echo $conf_option["use_gallery_according_to_for_options"]; ?>"
                                                                >
                                                                </button>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <span class="configurator__option__color-container__value" ></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                <?php endif; ?>


                                <?php if ( isset( $conf_option['popup']['type'] ) && $conf_option['popup']['type'] !== "" && isset( $conf_option['popup']['info_box_title'] ) && $conf_option['popup']['info_box_title'] !== "" ): ?>

                                    <button class="configurator__option__popup-button bg-gray-lighter flex relative group">
                                        <?php if ( isset( $conf_option['popup']['info_box_video_cover'] ) && $conf_option['popup']['info_box_video_cover'] !== "" ): ?>
                                            <?php $video_cover = wp_get_attachment_image_url( $conf_option['popup']['info_box_video_cover'], 'full', false ); ?>
                                            <div class="w-[7.5rem] h-[8.75rem] relative">
                                                <img src="<?php echo $video_cover; ?>" class="w-full h-full object-cover" />
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none" class="absolute top-1/2 -translate-y-1/2 left-0 right-0 mx-auto">
                                                    <g opacity="0.7">
                                                        <path d="M7.75 13.6652L14.0384 9.58896L7.75 5.51271V13.6652ZM10.0016 19.1779C8.68772 19.1779 7.45268 18.9262 6.29655 18.4229C5.1404 17.9196 4.13472 17.2365 3.2795 16.3736C2.42427 15.5108 1.74721 14.4961 1.24833 13.3297C0.749442 12.1632 0.5 10.9169 0.5 9.59065C0.5 8.26441 0.749334 7.0178 1.248 5.85084C1.74667 4.68386 2.42342 3.66876 3.27825 2.80554C4.1331 1.94229 5.13834 1.25889 6.29398 0.755335C7.44959 0.251779 8.68437 0 9.9983 0C11.3122 0 12.5473 0.25167 13.7034 0.755007C14.8596 1.25834 15.8652 1.94143 16.7205 2.80427C17.5757 3.66713 18.2527 4.68179 18.7516 5.84824C19.2505 7.01468 19.5 8.26103 19.5 9.58727C19.5 10.9135 19.2506 12.1601 18.752 13.3271C18.2533 14.494 17.5765 15.5091 16.7217 16.3724C15.8669 17.2356 14.8616 17.919 13.706 18.4226C12.5504 18.9261 11.3156 19.1779 10.0016 19.1779Z" fill="#FFFEFA"/>
                                                    </g>
                                                </svg>
                                            </div>
                                        <?php endif; ?>

                                        <div class="p-6">

                                            <?php if ( isset( $conf_option['popup']['info_box_title'] ) && $conf_option['popup']['info_box_title'] !== "" ): ?>
                                                <p class="text text-left pr-14">
                                                    <?php echo $conf_option['popup']['info_box_title']; ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ( isset( $conf_option['popup']['info_box_description'] ) && $conf_option['popup']['info_box_description'] !== "" ): ?>
                                                <p class="label text-gray-dark text-left pr-14 leading-[normal]">
                                                    <?php echo $conf_option['popup']['info_box_description']; ?>
                                                </p>
                                            <?php endif; ?>
                                            <span class="absolute top-6 right-6 w-4 h-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" class="absolute transition-opacity group-hover:opacity-0">
                                                    <g clip-path="url(#clip0_5380_104123)">
                                                        <path d="M8 7.33333H8.66667V12H7.33333V8.66667C6.96533 8.66667 6.66667 8.368 6.66667 8C6.66667 7.632 6.96533 7.33333 7.33333 7.33333H8ZM8 16C3.582 16 0 12.418 0 8C0 3.582 3.582 0 8 0C12.418 0 16 3.582 16 8C16 12.418 12.418 16 8 16ZM8 14.6667C11.682 14.6667 14.6667 11.682 14.6667 8C14.6667 4.318 11.682 1.33333 8 1.33333C4.318 1.33333 1.33333 4.318 1.33333 8C1.33333 11.682 4.318 14.6667 8 14.6667ZM7 5C7 4.448 7.444 4 8 4C8.552 4 9 4.444 9 5C9 5.552 8.556 6 8 6C7.448 6 7 5.556 7 5Z" fill="currentColor"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_5380_104123">
                                                        <rect width="16" height="16" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" class="absolute transition-opacity opacity-0 group-hover:opacity-100">
                                                    <path d="M8 0C3.58 0 0 3.58 0 8C0 12.42 3.58 16 8 16C12.42 16 16 12.42 16 8C16 3.58 12.42 0 8 0ZM8.67 8.48V12.01H7.33V8.68C6.96 8.68 6.67 8.37 6.67 8.01C6.67 7.65 6.97 7.34 7.34 7.34H8.67V8.49V8.48ZM8 6C7.45 6 7 5.55 7 5C7 4.45 7.45 4 8 4C8.55 4 9 4.45 9 5C9 5.55 8.55 6 8 6Z" fill="black"/>
                                                </svg>
                                            </span>

                                        </div>
                                    </button>

                                <?php endif; ?>
                                
                                <input type="hidden" name="<?php echo $options_key; ?>" data-fuse-with="<?php echo $conf_option["fuse_attributes_with"]?>"/>

                                <?php if ( isset( $conf_option['popup']['type'] ) && $conf_option['popup']['type'] !== "" ): ?>

                                    <?php 
                                        // $product_data = [];
                                        // foreach ( $conf_option['popup']['comparison_fields_values'] as $product_id => $values ) {
                                        //     $product = wc_get_product( $product_id );
                                        //     $product_data[] = $product;
                                        // }

                                        // var_dump($product_data);
                                    ?>

                                    <div class="configurator__option__popup">

                                        <div class="configurator__option__popup__container p-6 pb-8 bg-white rounded w-full lg:w-auto">

                                            <div class="flex pb-4 justify-between items-center<?php if ( $conf_option['popup']['type'] === "comparison" ): ?> border-b border-black/08<?php endif; ?>">
                                                <?php if ( $conf_option['popup']['type'] === "comparison" ): ?>
                                                    <h2 class="subheading"><?php _e( 'Product comparison' ); ?></h2>
                                                <?php endif; ?>

                                                <button class="configurator__option__popup__close p-1.5<?php if ( $conf_option['popup']['type'] === "text" ): ?> ml-auto<?php endif; ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                                        <rect x="0.780273" y="0.25" width="18.75" height="0.75" transform="rotate(45 0.780273 0.25)" fill="#9C9996"/>
                                                        <rect x="0.25" y="13.5082" width="18.75" height="0.75" transform="rotate(-45 0.25 13.5082)" fill="#9C9996"/>
                                                    </svg>
                                                </button>
                                            </div>

                                            <?php if ( $conf_option['popup']['type'] === "comparison" ): ?>

                                                <div class="configurator__option__popup__comparison__list flex flex-col gap-6 pt-10">

                                                    <div class="configurator__option__popup__comparison__list__row grid grid-cols-[repeat(_auto-fit,_minmax(0,_1fr))] gap-2 lg:gap-8 lg:flex">
                                                        <div class="configurator__option__popup__comparison__list__row__item hidden lg:block">
                                                            <span class="label"><?php _e( 'Product' ); ?></span>
                                                        </div>
                                                        <?php foreach ( $conf_option['popup']['comparison_fields_values'] as $product_id => $values ): ?>
                                                            <div class="configurator__option__popup__comparison__list__row__item">
                                                                <span class="block label mb-6"><?php echo get_the_title( $product_id ); ?></span>
                                                                
                                                                <div class="bg-gray-lighter">
                                                                    <img src="<?php echo get_the_post_thumbnail_url( $product_id ); ?>" class="w-full max-w-[14rem]" alt=""/>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <?php foreach ( $conf_option['popup']['comparison_fields'] as $field ): ?>
                                                        <div class="grid grid-cols-[repeat(_auto-fit,_minmax(0,_1fr))] gap-2 lg:gap-8 lg:flex">
                                                            <div class="configurator__option__popup__comparison__list__row__item hidden lg:block">
                                                                <span class="label"><?php echo $field; ?></span>
                                                            </div>
                                                            <?php foreach ( $conf_option['popup']['comparison_fields_values'] as $product_id => $values ): ?>
                                                                <div class="configurator__option__popup__comparison__list__row__item">
                                                                    <span class="label text-gray-dark"><?php echo $values[$field]; ?></span>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endforeach; ?>

                                                </div>

                                            <?php elseif ( $conf_option['popup']['type'] === "text" ): ?>

                                                <div class="prose prose-sm prose-headings:font-light prose-headings:mb-4 prose-img:mx-auto prose-img:my-12 lg:prose-img:my-16">
                                                    <?php //echo $conf_option['popup']['text_content']; ?>
                                                    <?php echo do_shortcode( $conf_option['popup']['text_content'] ); ?>
                                                     <style>
                                                    .modal-footer-area{
                                                        margin-top: 30px;
                                                        padding-top: 15px;
                                                        border-top: 1px solid #9C9996;
                                                        text-align: center;
                                                    }
                                                    .modal-footer-area p{
                                                        font-size: 14px;
                                                        color: #231F1E;
                                                        padding: 0;
                                                        margin: 0;
                                                        line-height: 24px;
                                                    }

                                                    .modal-footer-area p:first-child{
                                                        color: #9C9996;

                                                    }

                                                    .modal-footer-area p a {
                                                        text-decoration: none;
                                                    }
                                                </style>
                                                <div class="modal-footer-area">
                                                    <p>Have more questions?</p>
                                                     <p><a href="mailto:your@email.com">Contact us</a></p>
                                                </div>
                                                </div>

                                            <?php endif; ?>

                                        </div>
                                        
                                    </div>

                                <?php endif; ?>
                                
                            </div>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    </div>
                </form>
                <div class="configurator__footer bg-white-dark p-6 relative">

                    <?php		
                        $ship_status_text = get_post_meta( $post->ID, 'ship_status_text', true );
                        $ship_status_skus = get_post_meta( $post->ID, 'ship_status_skus', true );
                
                        if (!isset($ship_status_text) && !is_array($ship_status_text)) {
                            return;
                        }
                
                        $only_without_skus = false;
                
                        if (count($ship_status_text) == 1 && $ship_status_skus[0] == '') {
                            $only_without_skus = true;
                        }
                    ?>

                    <div class="configurator__footer__no-match">
                        <div class="mb-2">
                            <span class="label">No product found, please select all options.</span>
                        </div>
                        <button class="button w-full button--disabled" disabled>Add to cart</button>
                        <div 
                            class="mt-4 text-right shipping-status<?php if (!$only_without_skus) echo " shipping-status--with-sku"; ?>"
                            data-texts='<?php echo json_encode($ship_status_text); ?>'
                            data-skus='<?php echo json_encode($ship_status_skus); ?>'
                        >
                            <span class="label text-gray-dark">
                                <?php if ($only_without_skus): ?>
                                    <?php echo $ship_status_text[0]; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <div class="configurator__footer__default">
                        <div class="mb-2">
                            <span class="label">Select all desired options.</span>
                        </div>
                        <button class="button w-full button--disabled" disabled>Add to cart</button>
                        <div 
                            class="mt-4 text-right shipping-status<?php if (!$only_without_skus) echo " shipping-status--with-sku"; ?>"
                            data-texts='<?php echo json_encode($ship_status_text); ?>'
                            data-skus='<?php echo json_encode($ship_status_skus); ?>'
                        >
                            <span class="label text-gray-dark">
                                <?php if ($only_without_skus): ?>
                                    <?php echo $ship_status_text[0]; ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <?php global $product; ?>
                    <div class="configurator__footer__selected">
                        <form>
                            <div class="mb-4 pb-2 w-full border-b border-black/10">
                                <span class="label">Selected module</span>
                            </div>
                            <div>
                                
                                <div class="configurator__footer__selected__module flex mb-8">
                                    <div class="configurator__footer__selected__module__image">

                                    </div>
                                    <!-- <img id="selected_img" src="" class="w-24 h-24 object-cover"/> -->
                                    <div>
                                        <h4 id="selected_name" class="label text-black"></h4>
                                            <span id="selected_sku" class="accent text-gray-dark"></span>
                                        <div>
                                        <div class="configurator__footer__actions flex items-center mt-6">
                                            
                                            <button class="configurator__footer__actions__minus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                    <line x1="4.37114e-08" y1="5.5" x2="11" y2="5.5" stroke="#9C9996"/>
                                                </svg>
                                            </button>

                                            <input type="text" id="selected_input" class="configurator__footer__actions__input p-0 text-center w-12 bg-transparent text-black accent border-0 h-[13px]" value="1"/>

                                            <button class="configurator__footer__actions__plus">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 11 11" fill="none">
                                                    <g clip-path="url(#clip0_5380_104195)">
                                                        <line x1="5.67578" y1="2.18557e-08" x2="5.67578" y2="11" stroke="#9C9996"/>
                                                        <line x1="8.74228e-08" y1="5.32422" x2="11" y2="5.32422" stroke="#9C9996"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_5380_104195">
                                                        <rect width="11" height="11" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </button>

                                        </div>
                                    </div>
                                
                                </div>
                                <div class="flex flex-col ml-auto">
                                        <span class="label" id="selected_price"></span>
                                        <span class="label text-gray-dark">excl. VAT</span>
                                    </div>
                            </div>

                            <div class="configurator__footer__accessories">
                                <div class="mb-4 pb-2 w-full border-b border-black/10">
                                    <span class="label"><?php _e( 'Accessories', 'prado' ); ?></span>
                                </div>
                                <div class="configurator__footer__accessories__list">

                                </div>
                            </div>
                            
                            <button class="configurator__footer__add-to-cart button w-full">Add to cart</button>
                
                            <div 
                                class="mt-4 text-right shipping-status<?php if (!$only_without_skus) echo " shipping-status--with-sku"; ?>"
                                data-texts='<?php echo json_encode($ship_status_text); ?>'
                                data-skus='<?php echo json_encode($ship_status_skus); ?>'
                            >
                                <span class="label text-gray-dark">
                                    <?php if ($only_without_skus): ?>
                                        <?php echo $ship_status_text[0]; ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
