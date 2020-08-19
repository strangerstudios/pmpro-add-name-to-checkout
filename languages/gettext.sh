# Change every instance of pmpro-addon-slug below to match your actual plugin slug
#---------------------------
# This script generates a new pmpro.pot file for use in translations.
# To generate a new pmpro-addon-slug.pot, cd to the main /pmpro-addon-slug/ directory,
# then execute `languages/gettext.sh` from the command line.
# then fix the header info (helps to have the old pmpro.pot open before running script above)
# then execute `cp languages/pmpro-addon-slug.pot languages/pmpro-addon-slug.po` to copy the .pot to .po
# then execute `msgfmt languages/pmpro-addon-slug.po --output-file languages/pmpro-addon-slug.mo` to generate the .mo
#---------------------------
echo "Updating pmpro-add-name-to-checkout.pot... "
xgettext -j -o languages/pmpro-add-name-to-checkout.pot \
--default-domain=pmpro-add-name-to-checkout \
--language=PHP \
--keyword=_ \
--keyword=__ \
--keyword=_e \
--keyword=_ex \
--keyword=_n \
--keyword=_x \
--sort-by-file \
--package-version=1.0 \
--msgid-bugs-address="info@paidmembershipspro.com" \
$(find . -name "*.php")
echo "Done!"