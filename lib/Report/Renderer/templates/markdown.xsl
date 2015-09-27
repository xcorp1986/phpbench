<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns="http://www.w3.org/1999/xhtml">
    <xsl:output method="text" indent="no" encoding="UTF-8"/>
    <xsl:param name="title" select="string('PHPBench Suite Results')"/>

    <xsl:template match="/reports">
        <xsl:value-of select="$title" /><xsl:call-template name="newline" /><xsl:call-template name="repeat">
            <xsl:with-param name="output" select="string('=')" />
            <xsl:with-param name="count" select="string-length($title)" />
        </xsl:call-template>
        <xsl:call-template name="newline" />
        <xsl:call-template name="newline" />
        <xsl:apply-templates select="report" />
    </xsl:template>

    <xsl:template match="report">
        <xsl:value-of select="@title"/><xsl:call-template name="newline" /><xsl:call-template name="repeat">
            <xsl:with-param name="output" select="string('-')" />
            <xsl:with-param name="count" select="string-length(@title)" />
        </xsl:call-template>
        <xsl:call-template name="newline" />
        <xsl:call-template name="newline" />
        <xsl:value-of select="description" />
        <xsl:call-template name="newline" />
        <xsl:call-template name="newline" />
        <xsl:apply-templates select="table" />
    </xsl:template>

    <xsl:template match="table">
            <xsl:for-each select=".//row[1]/cell">
                <xsl:if test="position()!=1" xml:space="preserve"> | </xsl:if>
                <xsl:value-of select="@name" />
            </xsl:for-each>
            <xsl:call-template name="newline" />
            <xsl:for-each select=".//row[1]/cell"><xsl:if test="position()!=1" xml:space="preserve">|</xsl:if> --- </xsl:for-each>
            <xsl:call-template name="newline" />
            <xsl:for-each select=".//row">
                <xsl:for-each select=".//cell">
                    <xsl:if test="position()!=1" xml:space="preserve"> | </xsl:if>
                    <xsl:value-of select="text()" />
                </xsl:for-each>
                <xsl:call-template name="newline" />
            </xsl:for-each>
            <xsl:call-template name="newline" />
            <xsl:call-template name="newline" />
    </xsl:template>

    <!-- Repeat a character n times template -->
    <xsl:template name="repeat">
        <xsl:param name="output" />
        <xsl:param name="count" />
        <xsl:if test="$count &gt; 0">
            <xsl:value-of select="$output" />
            <xsl:call-template name="repeat">
                <xsl:with-param name="output" select="$output" />
                <xsl:with-param name="count" select="$count - 1" />
            </xsl:call-template>
        </xsl:if>
    </xsl:template>

    <xsl:template name="newline">
        <xsl:text>&#xa;</xsl:text>
    </xsl:template>
</xsl:stylesheet>
