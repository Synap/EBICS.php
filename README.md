Synap / EBICS
=============

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Synap/EBICS/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Synap/EBICS/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Synap/EBICS/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Synap/EBICS/?branch=master)
[![Build Status](https://travis-ci.org/Synap/EBICS.php.svg?branch=master)](https://travis-ci.org/Synap/EBICS.php)
[![Gitter](https://badges.gitter.im/Synap/EBICS.php.svg)](https://gitter.im/Synap/EBICS.php?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0adf2438-560e-4dae-899d-7ea44230cafb/big.png)](https://insight.sensiolabs.com/projects/0adf2438-560e-4dae-899d-7ea44230cafb)



Un projet de bibliothèque PHP pour le protocole EBICS (version française)

EBICS est LE standard du moment pour tout ce qui relève de l'automatisation des échanges bancaires. Cependant, ce
protocole étant encore jeune, il existe peu de bibliothèques sous licence libre. Par ailleurs, quelques variantes
existent entre les banques françaises et les banques allemandes.

Ce projet vise à développer une bibliothèque PHP compatible avec les banques françaises afin de proposer aux développeurs
un composant logiciel facilement exploitable pour leurs projets d'applications de gestion comptable, e-commerce, ou
autre...

La raison d'être de Synap System est d'encourager et d'aider le développement du logiciel libre. De ce fait, la licence
retenue pour ce projet est la [GNU Affero General Public](https://www.gnu.org/licenses/agpl-3.0.html).

Un système de double licence payante pour les entreprises qui jugeraient la licence GNU Affero General Public trop
contraignante est aussi envisagé. Contactez-nous si cela vous intéresse.

Faute de financement, aucune date de sortie n'est prévue pour le moment et le développement se fait au fil de l'eau.

Si vous souhaitez nous aider financièrement, si vous avez une suggestion ou si vous souhaitez contribuer, vous pouvez
envoyer un e-mail à l'adresse suivante: contact@synap.fr ou sur [gitter.im](https://gitter.im/Synap/EBICS?utm_source=share-link&utm_medium=link&utm_campaign=share-link)

Dons en bitcoins acceptés: 1isDyjadd2bvEWhADc2URZedvtnQnC3e2

Installation
============

Ce projet nécessite [composer](https://getcomposer.org/) pour l'installation des dépendances:

    git clone https://github.com/Synap/EBICS.php.git
    cd EBICS.php
    composer install

Une fois cette opération réalisée copiez le fichier `parameters.json-dist` dans un fichier `parameters.json` et éditez-le pour y insérer les paramètres de connexion au serveur EBICS.

Ensuite ajoutez les clés privées et les certificats selon la liste suivante:

- `test/fixtures/keys/A005/cert.pem`
- `test/fixtures/keys/A005/key.pem`
- `test/fixtures/keys/E002/cert.pem`
- `test/fixtures/keys/E002/key.pem`
- `test/fixtures/keys/X002/cert.pem`
- `test/fixtures/keys/X002/key.pem`

Vous pouvez alors tester les commandes suivantes:

- `app/console ebics:hev`
- `app/console ebics:hia`
- `app/console ebics:ini`

Attention! Ce projet est en cours de développement. N'utilisez ces commandes qu'à des fins de test et à vos risques et périls.

Liens
=====

- [ebics.org](http://www.ebics.org/)
- [Service gratuit de test](https://software.elcimai.com/efs/accueil-qualif.jsp)
- [Epics (bibliothèque en ruby)](https://github.com/railslove/epics)
- [Module EBICS pour OpenConcerto (JAVA)](https://code.google.com/p/openconcerto/source/browse/trunk/Modules/Module+EBICS/)
- [Un autre client EBICS en Java](http://sourceforge.net/projects/ebics/)
- [An Introduction to
XML Signature and XML Encryption
with XMLSec](http://users.dcc.uchile.cl/~pcamacho/tutorial/web/xmlsec/xmlsec.html)

