<?php
require_once __DIR__ . '/../../../app/block_content.php';
$p = block_content($block ?? [], $data ?? null);
$anchor = block_anchor(block_value($p, 'anchor'));
$email = block_value($p, 'contact_email');
$email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
$recipient = block_value($p, 'recipient_email', $email);
$recipient = filter_var($recipient, FILTER_VALIDATE_EMAIL) ? $recipient : $email;
$uid = 'inquiry-' . max(0, (int)($block['_render_index'] ?? 0));
$options = array_filter(array_map('trim', preg_split('/\r?\n/', block_value($p, 'service_options')) ?: []));
$delivery = block_value($p, 'delivery', 'server');
?>
<section<?= $anchor !== '' ? ' id="' . block_escape($anchor) . '"' : '' ?> class="contact section">
  <div class="shell contact-grid">
    <div class="contact-intro">
      <?php if (block_value($p, 'topline') !== ''): ?><p class="eyebrow light"><span class="section-number"><?= block_escape(block_value($p, 'section_number')) ?></span><?= block_escape(block_value($p, 'topline')) ?></p><?php endif; ?>
      <h2><?= block_lines(block_value($p, 'headline')) ?><?php if (block_value($p, 'headline_accent') !== ''): ?><br><span><?= block_lines(block_value($p, 'headline_accent')) ?></span><?php endif; ?></h2>
      <p><?= block_lines(block_value($p, 'intro')) ?></p>
      <?php if ($email !== ''): ?><a class="contact-email" href="mailto:<?= block_escape($email) ?>"><?= block_escape($email) ?> <span aria-hidden="true">&#8599;</span></a><?php endif; ?>
      <div class="contact-small-links">
        <?php if (block_value($p, 'contact_phone') !== ''): ?><a href="tel:<?= block_escape(preg_replace('/[^0-9+]/', '', block_value($p, 'contact_phone')) ?? '') ?>"><?= block_escape(block_value($p, 'contact_phone')) ?></a><?php endif; ?>
        <?php if (($url = block_url(block_value($p, 'social_url'))) !== '' && block_value($p, 'social_text') !== ''): ?><a href="<?= block_escape($url) ?>"><?= block_escape(block_value($p, 'social_text')) ?> &#8599;</a><?php endif; ?>
      </div>
    </div>
    <?php if ($delivery !== 'mailto'):
        $serverData = array_replace($p, ['layout' => 'standard', 'headline' => block_value($p, 'form_title'), 'intro' => '']);
        render('themes/default/blocks/contact_form.php', [
            'block' => $serverData, 'data' => $serverData,
            'contactFormStates' => $contactFormStates ?? [], 'currentSlug' => $currentSlug ?? '',
            'contactFormAction' => $contactFormAction ?? '',
            'contactTurnstileSiteKey' => $contactTurnstileSiteKey ?? '',
        ]);
    else: ?>
    <form class="inquiry-form" data-recipient="<?= block_escape($recipient) ?>" data-delivery="mailto">
      <div class="form-top"><span><?= block_escape(block_value($p, 'form_title')) ?></span><span aria-hidden="true">&#8599;</span></div>
      <?php if ($options !== []): ?><label for="<?= $uid ?>-service">Worum geht es?</label><select id="<?= $uid ?>-service" name="service"><?php foreach ($options as $option): ?><option><?= block_escape($option) ?></option><?php endforeach; ?></select><?php endif; ?>
      <?php if (block_value($p, 'show_project') === '1'): ?><div class="form-pair"><div><label for="<?= $uid ?>-event">Event oder Format</label><input id="<?= $uid ?>-event" name="event" type="text" maxlength="160" placeholder="z. B. Rennwochenende oder Livestream" required></div><div><label for="<?= $uid ?>-date">Zeitraum</label><input id="<?= $uid ?>-date" name="date" type="text" maxlength="100" placeholder="z. B. September 2027"></div></div><?php endif; ?>
      <?php if (block_value($p, 'show_message', '1') === '1'): ?><label for="<?= $uid ?>-message">Was sollte ich wissen?</label><textarea id="<?= $uid ?>-message" name="message" rows="4" maxlength="2500" placeholder="Ort, Umfang, Zielgruppe und weitere Details ..."></textarea><?php endif; ?>
      <?php if (block_value($p, 'show_name', '1') === '1'): ?><label for="<?= $uid ?>-name">Dein Name / deine Organisation</label><input id="<?= $uid ?>-name" name="name" type="text" autocomplete="name" maxlength="140" placeholder="Wer fragt an?" required><?php endif; ?>
      <?php if (block_value($p, 'show_email', '1') === '1'): ?><label for="<?= $uid ?>-email">Deine E-Mail-Adresse</label><input id="<?= $uid ?>-email" name="email" type="email" autocomplete="email" maxlength="190" required><?php endif; ?>
      <?php if (block_value($p, 'show_phone', '1') === '1'): ?><label for="<?= $uid ?>-phone">Telefon</label><input id="<?= $uid ?>-phone" name="phone" type="tel" autocomplete="tel" maxlength="80"><?php endif; ?>
      <button type="submit" class="button button-red"><?= block_escape(block_value($p, 'submit_label', 'Anfrage in E-Mail oeffnen')) ?> <span aria-hidden="true">&#8599;</span></button>
      <button type="button" class="copy-button">Anfragetext kopieren</button>
      <p class="form-note">Oeffnet deine E-Mail-App mit einer vorbereiteten Nachricht. Du versendest sie selbst.</p><p class="form-status" role="status" aria-live="polite"></p>
    </form>
    <?php endif; ?>
  </div>
</section>
