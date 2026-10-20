#!/usr/bin/env bash
# FAQ — injection des questions/réponses comme meta JSON

step "Populating FAQ..."

FAQ_ID=$(page_id "FAQ")

FAQ_JSON=$(cat <<'ENDJSON'
[
  {
    "question": "Quels sont vos délais de livraison ?",
    "answer": "Nous expédions vos commandes sous 24 à 48h ouvrées. La livraison standard prend ensuite 2 à 4 jours ouvrés en France métropolitaine. Un email de confirmation avec numéro de suivi vous est envoyé dès l'expédition."
  },
  {
    "question": "Comment conserver mes graines pour garantir leur germination ?",
    "answer": "Conservez vos graines dans un endroit frais, sec et à l'abri de la lumière — idéalement au réfrigérateur dans un sachet hermétique avec un sachet de gel de silice. Dans ces conditions, les graines restent viables 3 à 5 ans. Évitez les variations de température et l'humidité."
  },
  {
    "question": "Vos plants sont-ils certifiés et traités ?",
    "answer": "Tous nos plants sont élevés en serre sans pesticides ni produits chimiques. Nous pratiquons une culture raisonnée. Les graines proviennent de notre propre sélection ou de semenciers spécialisés de confiance. Aucun traitement systématique n'est appliqué."
  },
  {
    "question": "Puis-je commander des plants en dehors de la saison de plantation ?",
    "answer": "La disponibilité des plants suit le calendrier horticole (mars à juin principalement). En dehors de cette période, nous proposons uniquement des graines. Abonnez-vous à notre newsletter pour être informé de la prochaine mise en vente de plants."
  },
  {
    "question": "Comment est calculée la force d'un piment (Scoville) ?",
    "answer": "L'échelle de Scoville mesure la concentration en capsaïcine. Un poivron est à 0 SHU, un jalapeño entre 2 500 et 8 000 SHU, un Habanero autour de 350 000 SHU, et le Carolina Reaper dépasse 2 000 000 SHU. La mesure est réalisée par chromatographie en phase liquide."
  },
  {
    "question": "Proposez-vous des commandes en gros ou pour professionnels ?",
    "answer": "Oui, nous proposons des tarifs dégressifs pour les professionnels (restaurateurs, épiceries fines, maraîchers). Contactez-nous directement via le formulaire de contact en précisant vos besoins et volumes envisagés."
  },
  {
    "question": "Quelle est votre politique de retour ?",
    "answer": "Vous disposez de 14 jours après réception pour retourner un article non ouvert dans son emballage d'origine. Les graines et plants ne peuvent être retournés pour des raisons sanitaires. En cas de produit endommagé à la livraison, contactez-nous sous 48h avec photos."
  },
  {
    "question": "Livrez-vous à l'international ?",
    "answer": "Nous livrons dans toute l'Union Européenne. Pour les envois de graines hors UE, des restrictions phytosanitaires peuvent s'appliquer selon le pays destinataire. Les plants ne sont expédiés qu'en France métropolitaine."
  }
]
ENDJSON
)

$WP post meta update "$FAQ_ID" _faq_items "$FAQ_JSON"
