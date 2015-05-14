<?xml version="1.0" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:output method="xml" indent="yes"/>

  <xsl:template match="/">
    <ebicsHEVRequest xmlns="http://www.ebics.org/H000">
      <HostID><xsl:value-of select="$HostID" /></HostID>
    </ebicsHEVRequest>
  </xsl:template>

</xsl:stylesheet>
