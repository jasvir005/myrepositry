<?php
// config.sef.php : configuration file for sh404SEF for Joomla 1.5.x
// 3.6.4.1481
// saved at: 2012-11-24 10:41:12
// by: admin (id: 42 )
// domain: http://tr.loc

if (!defined('_JEXEC')) die('Direct Access to this location is not allowed.');

$version = '3.6.4.1481';
$Enabled = '0';
$replacement = '-';
$pagerep = '-';
$stripthese = ',|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|„|‹|’|‘|“|”|•|›|«|´|»|°';
$shReplacements = 'Š|S, Œ|O, Ž|Z, š|s, œ|oe, ž|z, Ÿ|Y, ¥|Y, µ|u, À|A, Á|A, Â|A, Ã|A, Ä|A, Å|A, Æ|A, Ç|C, È|E, É|E, Ê|E, Ë|E, Ì|I, Í|I, Î|I, Ï|I, Ð|D, Ñ|N, Ò|O, Ó|O, Ô|O, Õ|O, Ö|O, Ø|O, Ù|U, Ú|U, Û|U, Ü|U, Ý|Y, ß|s, à|a, á|a, â|a, ã|a, ä|a, å|a, æ|a, ç|c, è|e, é|e, ê|e, ë|e, ì|i, í|i, î|i, ï|i, ð|o, ñ|n, ò|o, ó|o, ô|o, õ|o, ö|o, ø|o, ù|u, ú|u, û|u, ü|u, ý|y, ÿ|y, ß|ss, ă|a, ş|s, ţ|t, ț|t, Ț|T, Ș|S, ș|s, Ş|S';
$suffix = '.html';
$addFile = '';
$friendlytrim = '-|.';
$LowerCase = false;
$ShowSection = false;
$ShowCat = true;
$UseAlias = '1';
$page404 = 0;
$predefined = array();
$skip = array();
$nocache = array("events");
$shDoNotOverrideOwnSef = array();
$shLog404Errors = true;
$shUseURLCache = true;
$shMaxURLInCache = 10000;
$shTranslateURL = true;
$shInsertLanguageCode = true;
$notTranslateURLList = array();
$notInsertIsoCodeList = array();
$shInsertGlobalItemidIfNone = false;
$shInsertTitleIfNoItemid = false;
$shAlwaysInsertMenuTitle = false;
$shAlwaysInsertItemid = false;
$shDefaultMenuItemName = '';
$shAppendRemainingGETVars = true;
$shVmInsertShopName = '0';
$shInsertProductId = false;
$shVmUseProductSKU = false;
$shVmInsertManufacturerName = false;
$shInsertManufacturerId = false;
$shVMInsertCategories = 1;
$shVmAdditionalText = true;
$shVmInsertFlypage = true;
$shInsertCategoryId = false;
$shInsertNumericalId = '0';
$shInsertNumericalIdCatList = array("");
$shRedirectNonSefToSef = true;
$shRedirectJoomlaSefToSef = false;
$shConfig_live_secure_site = '';
$shActivateIJoomlaMagInContent = true;
$shInsertIJoomlaMagIssueId = false;
$shInsertIJoomlaMagName = false;
$shInsertIJoomlaMagMagazineId = false;
$shInsertIJoomlaMagArticleId = false;
$shInsertCBName = '0';
$shCBInsertUserName = '0';
$shCBInsertUserId = '1';
$shCBUseUserPseudo = '1';
$shLMDefaultItemid = 0;
$shInsertFireboardName = '1';
$shFbInsertCategoryName = true;
$shFbInsertCategoryId = false;
$shFbInsertMessageSubject = true;
$shFbInsertMessageId = true;
$shInsertMyBlogName = '0';
$shMyBlogInsertPostId = '1';
$shMyBlogInsertTagId = '0';
$shMyBlogInsertBloggerId = '1';
$shInsertDocmanName = false;
$shDocmanInsertDocId = true;
$shDocmanInsertDocName = true;
$shDMInsertCategories = 1;
$shDMInsertCategoryId = false;
$shEncodeUrl = false;
$guessItemidOnHomepage = false;
$shForceNonSefIfHttps = false;
$shRewriteMode = '0';
$shRewriteStrings = array("/","/index.php/","/index.php?/");
$shRecordDuplicates = true;
$shRemoveGeneratorTag = '1';
$shPutH1Tags = false;
$shMetaManagementActivated = '1';
$shInsertContentTableName = '1';
$shContentTableName = 'Table';
$shAutoRedirectWww = 0;
$shVmInsertProductName = true;
$shForcedHomePage = '';
$shInsertContentBlogName = '0';
$shContentBlogName = '';
$shInsertMTreeName = '0';
$shMTreeInsertListingName = '1';
$shMTreeInsertListingId = '1';
$shMTreePrependListingId = '1';
$shMTreeInsertCategories = '1';
$shMTreeInsertCategoryId = '0';
$shMTreeInsertUserName = '1';
$shMTreeInsertUserId = '1';
$shInsertNewsPName = false;
$shNewsPInsertCatId = false;
$shNewsPInsertSecId = false;
$shInsertRemoName = false;
$shRemoInsertDocId = true;
$shRemoInsertDocName = true;
$shRemoInsertCategories = 1;
$shRemoInsertCategoryId = false;
$shCBShortUserURL = '0';
$shKeepStandardURLOnUpgrade = true;
$shKeepCustomURLOnUpgrade = true;
$shKeepMetaDataOnUpgrade = true;
$shKeepModulesSettingsOnUpgrade = true;
$shMultipagesTitle = '1';
$encode_page_suffix = '';
$encode_space_char = '-';
$encode_lowercase = false;
$encode_strip_chars = ',|~|!|@|%|^|(|)|<|>|:|;|{|}|[|]|&|`|„|‹|’|‘|“|”|•|›|«|´|»|°';
$spec_chars_d = 'Š,Œ,Ž,š,œ,ž,Ÿ,¥,µ,À,Á,Â,Ã,Ä,Å,Æ,Ç,È,É,Ê,Ë,Ì,Í,Î,Ï,Ð,Ñ,Ò,Ó,Ô,Õ,Ö,Ø,Ù,Ú,Û,Ü,Ý,ß,à,á,â,ã,ä,å,æ,ç,è,é,ê,ë,ì,í,î,ï,ð,ñ,ò,ó,ô,õ,ö,ø,ù,ú,û,ü,ý,ÿ,ă,ş,ţ,ț,Ț,Ș,ș,Ş,';
$spec_chars = 'S,O,Z,s,oe,z,Y,Y,u,A,A,A,A,A,A,A,C,E,E,E,E,I,I,I,I,D,N,O,O,O,O,O,O,U,U,U,U,Y,ss,a,a,a,a,a,a,a,c,e,e,e,e,i,i,i,i,o,n,o,o,o,o,o,o,u,u,u,u,y,y,a,s,t,t,T,S,s,S,';
$content_page_format = '%s-%d';
$content_page_name = 'Page-';
$shKeepConfigOnUpgrade = true;
$shSecEnableSecurity = '0';
$shSecLogAttacks = true;
$shSecOnlyNumVars = array("itemid","limit","limitstart");
$shSecAlphaNumVars = array();
$shSecNoProtocolVars = array("task","option","no_html","mosmsg","lang");
$shSecCheckHoneyPot = false;
$shSecHoneyPotKey = '';
$shSecEntranceText = '<p>Sorry. You are visiting this site from a suspicious IP address, which triggered our protection system.</p>
    <p>If you <strong>ARE NOT</strong> a malware robot of any kind, please accept our apologies for the inconvenience. You can access the page by clicking here : ';
$shSecSmellyPotText = 'The following link is here to further trap malicious internet robots, so please don\'t click on it : ';
$monthsToKeepLogs = 1;
$shSecActivateAntiFlood = true;
$shSecAntiFloodOnlyOnPOST = false;
$shSecAntiFloodPeriod = 10;
$shSecAntiFloodCount = 10;
$shLangTranslateList = array("en-GB"=>"0");
$shLangInsertCodeList = array("en-GB"=>"0");
$defaultComponentStringList = array();
$pageTexts = array("en-GB"=>"Page-%s");
$shAdminInterfaceType = 1;
$shInsertNoFollowPDFPrint = true;
$shInsertReadMorePageTitle = false;
$shMultipleH1ToH2 = '0';
$shVmUsingItemsPerPage = true;
$shSecCheckPOSTData = true;
$shSecCurMonth = 0;
$shSecLastUpdated = 0;
$shSecTotalAttacks = 0;
$shSecTotalConfigVars = 0;
$shSecTotalBase64 = 0;
$shSecTotalScripts = 0;
$shSecTotalStandardVars = 0;
$shSecTotalImgTxtCmd = 0;
$shSecTotalIPDenied = 0;
$shSecTotalUserAgentDenied = 0;
$shSecTotalFlooding = 0;
$shSecTotalPHP = 0;
$shSecTotalPHPUserClicked = 0;
$shInsertSMFName = true;
$shSMFItemsPerPage = 20;
$shInsertSMFBoardId = true;
$shInsertSMFTopicId = true;
$shinsertSMFUserName = false;
$shInsertSMFUserId = true;
$appendToPageTitle = '';
$prependToPageTitle = '';
$debugToLogFile = false;
$debugStartedAt = 0;
$debugDuration = 3600;
$shInsertOutboundLinksImage = '0';
$shImageForOutboundLinks = 'external-black.png';
$useCatAlias = '1';
$useSecAlias = false;
$useMenuAlias = '1';
$alwaysAppendItemsPerPage = false;
$redirectToCorrectCaseUrl = true;
$jclInsertEventId = false;
$jclInsertCategoryId = false;
$jclInsertCalendarId = false;
$jclInsertCalendarName = false;
$jclInsertDate = false;
$jclInsertDateInEventView = true;
$ContentTitleShowSection = false;
$ContentTitleShowCat = true;
$ContentTitleUseAlias = '0';
$ContentTitleUseCatAlias = '0';
$ContentTitleUseSecAlias = false;
$pageTitleSeparator = ' | ';
$ContentTitleInsertArticleId = '0';
$shInsertContentArticleIdCatList = array("");
$shJSInsertJSName = '1';
$shJSShortURLToUserProfile = '1';
$shJSInsertUsername = '1';
$shJSInsertUserFullName = '0';
$shJSInsertUserId = '0';
$shJSInsertGroupCategory = '1';
$shJSInsertGroupCategoryId = '0';
$shJSInsertGroupId = '0';
$shJSInsertGroupBulletinId = '0';
$shJSInsertDiscussionId = '1';
$shJSInsertMessageId = '1';
$shJSInsertPhotoAlbum = '1';
$shJSInsertPhotoAlbumId = '0';
$shJSInsertPhotoId = '1';
$shJSInsertVideoCat = '1';
$shJSInsertVideoCatId = '0';
$shJSInsertVideoId = '1';
$shFbInsertUserName = true;
$shFbInsertUserId = true;
$shFbShortUrlToProfile = true;
$shPageNotFoundItemid = 0;
$autoCheckNewVersion = true;
$error404SubTemplate = 'index';
$enablePageId = true;
$compEnablePageId = array("contact","content","newsfeeds","poll","user","weblinks");
$analyticsEnabled = false;
$analyticsReportsEnabled = true;
$analyticsType = 'ga';
$analyticsId = '';
$analyticsExcludeIP = array();
$analyticsMaxUserLevel = '';
$analyticsUser = '';
$analyticsPassword = '';
$analyticsAccount = '';
$analyticsProfile = '';
$autoCheckNewAnalytics = true;
$analyticsDashboardDateRange = 'week';
$analyticsEnableTimeCollection = true;
$analyticsEnableUserCollection = true;
$analyticsDashboardDataType = 'pageviews';
$slowServer = false;
$useJoomsefRouter = array();
$useAcesefRouter = array();
$insertShortlinkTag = true;
$insertRevCanTag = false;
$insertAltShorterTag = false;
$canReadRemoteConfig = '0';
$stopCreatingShurls = false;
$shurlBlackList = '';
$shurlNonSefBlackList = '';
$includeContentCat = '2';
$includeContentCatCategories = '4';
$contentCategoriesSuffix = 'all';
$contentTitleIncludeCat = '0';
$useContactCatAlias = '0';
$contactCategoriesSuffix = 'all';
$includeContactCat = '5';
$includeContactCatCategories = '2';
$useWeblinksCatAlias = '0';
$weblinksCategoriesSuffix = 'all';
$includeWeblinksCat = '2';
$includeWeblinksCatCategories = '2';
$liveSites = array("en-GB"=>"");
$alternateTemplate = '';
$useJoomlaRouter = array();
$slugForUncategorizedContent = '0';
$slugForUncategorizedContact = '0';
$slugForUncategorizedWeblinks = '0';
$enableMultiLingualSupport = false;
$enableOpenGraphData = false;
$ogEnableDescription = true;
$ogType = 'article';
$ogImage = '';
$ogEnableSiteName = true;
$ogSiteName = '';
$ogEnableLocation = false;
$ogLatitude = '';
$ogLongitude = '';
$ogStreetAddress = '';
$ogLocality = '';
$ogPostalCode = '';
$ogRegion = '';
$ogCountryName = '';
$ogEnableContact = false;
$ogEmail = '';
$ogPhoneNumber = '';
$ogFaxNumber = '';
$fbAdminIds = '';
$socialButtonType = 'facebook';
$insertPaginationTags = '1';
$UrlCacheHandler = 'File';
$displayUrlCacheStats = false;
$analyticsUserGroups = null;
$fileAccessStatus = array("COM_SH404SEF_WRITEABLE","COM_SH404SEF_WRITEABLE","COM_SH404SEF_WRITEABLE","COM_SH404SEF_WRITEABLE","COM_SH404SEF_UNWRITEABLE","COM_SH404SEF_WRITEABLE","COM_SH404SEF_WRITEABLE");
?>