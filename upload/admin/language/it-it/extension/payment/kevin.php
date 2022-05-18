<?php
/*
* 2020 kevin. payment  for OpenCart version 3.0.x.x
* @version 1.0.1.5
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
*  @author 2020 kevin. <help@kevin.eu>
*  @copyright kevin.
*  @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
*/
// Heading
$_['heading_title'] = '<span style="font-weight: 700; color:red;">kevin. </span> Payment (versione '.KEVIN_VERSION.')';

// Text
$_['text_clear_success'] = 'Successo: Hai cancellato con successo il registro!';
$_['text_edit'] = 'Modifica kevin. modulo di pagamento';
$_['text_extension'] = 'Estensioni';
$_['text_kevin'] = '<a href="https://www.kevin.eu/" target="_blank"><img src="view/image/payment/kevin.png" alt="kevin." title="kevin." style="border: 0px solid #ffffff; height: 30px;" /></a>';
$_['text_left'] = 'Sinistra';
$_['text_payment'] = 'Pagamento';
$_['text_payment_log'] = 'Registro dei pagamenti';
$_['text_refund_log'] = 'Registro dei rimborsi';
$_['text_right'] = 'A destra';
$_['text_select_action'] = '- Selezionare Azione-';
$_['text_select_status'] = '-Selezionare lo stato-';
$_['text_success'] = 'Successo: Hai modificato i dettagli del modulo kevin.';

// Entry
$_['entry_bank_name_enabled'] = 'Nome della banca';
$_['entry_client_company'] = 'Nome dell\'azienda cliente:';
$_['entry_client_endpointSecret'] = 'EndpointSecret';
$_['entry_client_iban'] = 'Numero di conto del cliente:';
$_['entry_client_id'] = 'Client ID:';
$_['entry_client_secret'] = 'Client Secret:';
$_['entry_completed_status'] = 'Completa';
$_['entry_created_refund_action'] = 'Pronto per il rimborso';
$_['entry_failed_status'] = 'Fallito';
$_['entry_full_refund_action'] = 'Completamente rimborsato';
$_['entry_general'] = 'Generale';
$_['entry_geo_zone'] = 'Geo Zone:';
$_['entry_image'] = 'Pagamento kevin. Immagine del logo';
$_['entry_image_height'] = 'Altezza massima dell\'immagine in px';
$_['entry_image_width'] = 'Larghezza massima dell\'immagine in px';
$_['entry_instructions'] = 'Istruzioni per il pagamento';
$_['entry_instruction_title'] = 'Titolo dell\'istruzione';
$_['entry_kevin_instruction'] = 'Istruzioni per il passo di conferma. HTML non supportato';
$_['entry_kevin_title'] = 'Metodo di pagamento Titolo';
$_['entry_log'] = 'kevin. log:';
$_['entry_order_status'] = 'Stato dell\'ordine:';
$_['entry_order_statuses'] = 'Stati degli ordini';
$_['entry_partial_refund_action'] = 'Parzialmente rimborsato';
$_['entry_partial_refund_status'] = 'Stato dell\'ordine parzialmente rimborsato';
$_['entry_payment_log'] = 'Registro pagamenti';
$_['entry_pending_status'] = 'In attesa di';
$_['entry_position'] = 'Posizione del logo di pagamento';
$_['entry_redirect_preferred'] = 'Reindirizzamento preferito';
$_['entry_refunded_status'] = 'Stato dell\'ordine rimborsato';
$_['entry_refund_actions'] = 'Azioni di rimborso';
$_['entry_refund_log'] = 'Registro di rimborso';
$_['entry_refund_status'] = 'Stato di rimborso';
$_['entry_sort_order'] = 'Ordine di ordinamento:';
$_['entry_started_status'] = 'Iniziato';
$_['entry_status'] = 'Stato:';
$_['entry_total'] = 'Totale:';

// Error
$_['error_bcmod'] = 'Non è possibile convalidare l\'account No. perché il modulo PHP &quot;bcmath&quot; non è installato sul tuo server! Per favore installa il modulo &quot;bcmath&quot;, o chiedi al tuo fornitore di server di installare il modulo "bcmath".';
$_['error_client_company'] = 'Nome dell\'azienda cliente richiesto!';
$_['error_client_c_symbol'] = 'I caratteri speciali nel nome del cliente non sono accettabili!';
$_['error_client_endpointSecret'] = 'È necessaria la firma del cliente!';
$_['error_client_iban_empty'] = 'N. di conto cliente richiesto!';
$_['error_client_iban_valid'] = 'Numero di conto cliente non valido!';
$_['error_client_id'] = 'È necessario il codice cliente!';
$_['error_client_secret'] = 'Segreto del cliente richiesto!';
$_['error_completed_status'] = 'Stato dell\'ordine richiesto!';
$_['error_created_action'] = 'Azione di rimborso richiesta!';
$_['error_failed_status'] = 'Stato dell\'ordine richiesto!';
$_['error_partial_action'] = 'Azione di rimborso richiesta!';
$_['error_partial_status'] = 'Stato dell\'ordine richiesto!';
$_['error_payment_log_warning'] = 'Attenzione: Il tuo file di registro dei pagamenti %s è %s!';
$_['error_pending_status'] = 'Stato dell\'ordine richiesto!';
$_['error_permission'] = 'Attenzione: Non hai il permesso di modificare il pagamento kevin.';
$_['error_refunded_action'] = 'Azione di rimborso richiesta!';
$_['error_refunded_status'] = 'Stato dell\'ordine richiesto!';
$_['error_refund_log_warning'] = 'Attenzione: Il tuo file di registro del rimborso %s è %s!';
$_['error_started_status'] = 'Stato dell\'ordine richiesto!';
$_['error_title'] = 'Titolo di pagamento, o logo di pagamento richiesto!';
$_['error_warning'] = 'Controllate attentamente le impostazioni per eventuali errori!';

// Help
$_['help_bank_name_enbl'] = 'Abilita il nome della banca nella pagina di checkout.';
$_['help_bank_title'] = 'Si può solo aggiungere il logo di una banca al posto del titolo del metodo di pagamento.';
$_['help_client_endpointSecret'] = 'Il tuo Endpoint Secret da kevin. dashboard.';
$_['help_client_id'] = 'Il tuo ID cliente da kevin. cruscotto.';
$_['help_client_secret'] = 'Il segreto del tuo cliente da kevin. dashboard.';
$_['help_height'] = 'Imposta l\'altezza massima dell\'immagine del logo di pagamento in px per il metodo di pagamento nella fase di pagamento della cassa. La larghezza dell\'immagine sarà cambiata proporzionalmente.';
$_['help_iban_format'] = 'Formato del numero di conto Per la Lituania devono essere due lettere e 18 numeri. Esempio: LT599386327515536498.';
$_['help_log'] = 'Se kevin. log è abilitato i file kevin_payment.log e kevin_refund.log saranno salvati. Puoi facilmente controllarli, scaricarli o cancellarli.';
$_['help_position'] = 'Posizione del logo del metodo di pagamento accanto al nome del metodo di pagamento.';
$_['help_total'] = 'Importo minimo dell\'ordine';
$_['help_width'] = 'Imposta la larghezza massima dell\'immagine del logo di pagamento in px per il metodo di pagamento nella fase di pagamento della cassa. L\'altezza dell\'immagine sarà cambiata proporzionalmente.';
