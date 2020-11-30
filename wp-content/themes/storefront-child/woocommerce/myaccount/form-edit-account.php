<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined('ABSPATH') || exit;

$userSex = get_user_meta($user->ID, 'sex', true);

do_action('woocommerce_before_edit_account_form'); ?>

<div class="row">
    <div class="col">
        <fieldset class="bg-white">
            <?php
            ob_start();
            echo do_shortcode('[avatar_upload]');
            $output = ob_get_clean();
            echo preg_replace('~>Upload<~', '>Загрузить аватар<', $output);
            $output = null;
            ?>
        </fieldset>
        <form class="woocommerce-EditAccountForm edit-account" action=""
              method="post" <?php do_action('woocommerce_edit_account_form_tag'); ?> >

            <?php do_action('woocommerce_edit_account_form_start'); ?>

            <div class="row">
                <div class="col-lg-6 col-12 mb-lg-0 mb-4">
                    <label for="account_first_name"><?php esc_html_e('First name', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="text" class="form-control"
                           name="account_first_name" id="account_first_name" autocomplete="given-name"
                           value="<?php echo esc_attr($user->first_name); ?>"/>
                </div>
                <div class="col-lg-6 col-12">
                    <label for="account_last_name"><?php esc_html_e('Last name', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="text" class="form-control"
                           name="account_last_name"
                           id="account_last_name" autocomplete="family-name"
                           value="<?php echo esc_attr($user->last_name); ?>"/>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="account_display_name"><?php esc_html_e('Display name', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="text" class="form-control"
                           name="account_display_name" id="account_display_name"
                           value="<?php echo esc_attr($user->display_name); ?>"/>
                    <span><em><?php esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce'); ?></em></span>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="account_email"><?php esc_html_e('Email address', 'woocommerce'); ?>&nbsp;<span
                                class="required">*</span></label>
                    <input type="email" class="form-control"
                           name="account_email"
                           id="account_email" autocomplete="email" value="<?php echo esc_attr($user->user_email); ?>"/>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label>Пол</label>
                    <div>
                        <label class="form-check-label pure-material-radio mr-4" for="account_sex_f">
                            <input type="radio" class="form-check-input" name="account_sex" id="account_sex_f"
                                   value="female"<?php echo ($userSex == 'female') ? ' checked' : ''; ?>>
                            <span>Женский</span>
                        </label>
                        <label class="form-check-label pure-material-radio" for="account_sex_m">
                            <input type="radio" class="form-check-input" name="account_sex" id="account_sex_m"
                                   value="male" <?php echo ($userSex == 'male') ? ' checked' : ''; ?>>
                            <span>Мужской</span>
                        </label>
                    </div>
                </div>
            </div>
            <fieldset class="bg-white">
                <legend class="m-0 bg-white"><?php esc_html_e('Password change', 'woocommerce'); ?></legend>

                <div class="row">
                    <div class="col-12">
                        <label for="password_current"><?php esc_html_e('Current password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                        <input type="password" class="form-control"
                               name="password_current" id="password_current" autocomplete="off"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="password_1"><?php esc_html_e('New password (leave blank to leave unchanged)', 'woocommerce'); ?></label>
                        <input type="password" class="form-control"
                               name="password_1" id="password_1" autocomplete="off"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="password_2"><?php esc_html_e('Confirm new password', 'woocommerce'); ?></label>
                        <input type="password" class="form-control"
                               name="password_2" id="password_2" autocomplete="off"/>
                    </div>
                </div>
            </fieldset>

            <?php do_action('woocommerce_edit_account_form'); ?>

            <div class="row mt-4">
                <div class="col-12 text-center">
                    <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
                    <button type="submit" class="woocommerce-Button m-auto" name="save_account_details"
                            value="<?php esc_attr_e('Save changes', 'woocommerce'); ?>"><?php esc_html_e('Save changes', 'woocommerce'); ?></button>
                    <input type="hidden" name="action" value="save_account_details"/>
                </div>
            </div>

            <?php do_action('woocommerce_edit_account_form_end'); ?>
        </form>
    </div>
</div>


<?php do_action('woocommerce_after_edit_account_form'); ?>
