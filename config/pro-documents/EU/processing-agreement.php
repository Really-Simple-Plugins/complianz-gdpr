<?php
defined('ABSPATH') or die("you do not have acces to this page!");
$this->document_elements['processing-eu'] = array(
    array(
        'subtitle' => _x('The undersigned:', 'Legal document processing agreement', 'complianz'),
        'content' => '1. [organisation_name]',
    ),
    array(
        'content' => _x('hereinafter referred to as: Controller', 'Legal document processing agreement', 'complianz'),
    ),
    array(
        'content' => '<b>' . _x('and', 'Legal document processing agreement', 'complianz') . '</b>',
    ),
    array(
        'content' => '2. [name_of_processor]',
    ),
    array(
        'content' => _x('hereinafter referred to as: Processor', 'Legal document processing agreement', 'complianz'),
    ),
    array(
        'content' => _x('hereinafter jointly referred to as: Parties; ', 'Legal document processing agreement', 'complianz'),
    ),
    array(
        'subtitle' => _x('WHEREAS:', 'Legal document processing agreement', 'complianz'),
        'content' =>
            '<ul>
                        <li>' . _x('Insofar as the Contractor processes Personal Data on behalf of the Client within the scope of the Agreement, the Client qualifies as the Controller for the Processing of Personal Data and the Contractor as the Processor, pursuant to Article 4 (7) and (8) of the Regulation;', 'Legal document processing agreement', 'complianz') . '</li>
                        <li>' . _x('The Parties to this Data Processing Agreement, within the meaning of Article 28 paragraph 3 of the Regulation, wish to record their agreements on the Processing of Personal Data.', 'Legal document processing agreement', 'complianz') . '</li>
                    </ul>',
    ),
    'agree-that-title' => array(
        'subtitle' => _x('Agree as follows:', 'Legal document processing agreement', 'complianz'),
    ),
    'def-1' => array(
        'title' => _x('Definitions', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('The following terms used in this Data Processing Agreement shall have the meaning hereby assigned to them:', 'Legal document processing agreement', 'complianz'),
    ),
    'def-2' => array(
        'subtitle' => _x('Data Subject', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('The person to whom Personal Data relates', 'Legal document processing agreement', 'complianz')
    ),
    'def-3' => array(
        'subtitle' => _x('Personal Data Breach', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('A breach of security that accidentally or unlawfully results in the destruction, loss, alteration or unauthorised disclosure or access to personal data transmitted, stored or otherwise processed.', 'Legal document processing agreement', 'complianz'),
    ),
    'def-4' => array(
        'subtitle' => _x('Agreement', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('The agreement between the Controller and the Processor.', 'Legal document processing agreement', 'complianz'),
    ),
    'def-5' => array(
        'subtitle' => _x('Personal Data', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('Any information relating to an identified or identifiable natural person that the Processor processes on behalf of the Controller within the scope of the Agreement.', 'Legal document processing agreement', 'complianz'),
    ),
    'def-6' => array(
        'subtitle' => _x('Regulation', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('Regulation (EU) 2016/679 of 27 April 2016 of the European Parliament and of the Council on the protection of natural persons with regard to the processing of personal data and on the free movement of such data, and repealing Directive 95/46/EC (GDPR).', 'Legal document processing agreement', 'complianz'),
    ),
    'def-7' => array(
        'subtitle' => _x('Data Processing Agreement', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('This agreement including its recitals and annexes.', 'Legal document processing agreement', 'complianz'),
    ),
    'def-8' => array(
        'subtitle' => _x('Processing', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('Any operation or any set of operations relating to Personal Data within the scope of the Agreement, carried out by means of automated processes or otherwise, such as collection, recording, organisation, structuring, storage, adaptation or alteration, retrieval, consultation, use, disclosure by means of transmission, disseminating or otherwise making available, aligning or combining, restriction, erasure or destruction. ', 'Legal document processing agreement', 'complianz'),
    ),

    'subject-processing-agreement' => array(
        'title' => _x('Subject of this Data Processing Agreement ', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),
    'subject-processing-agreement-1' => array(
        'subtitle' => '',
        'content' => _x('This Data Processing Agreement regulates the Processing of Personal Data by the Processor within the scope of the Agreement.', 'Legal document processing agreement', 'complianz'),
    ),
    'subject-processing-agreement-2' => array(
        'subtitle' => '',
        'content' => _x('The nature and the purpose of the Processing, the type of Personal Data, and the categories of Data Subjects are set out in Annex 1. ', 'Legal document processing agreement', 'complianz'),
    ),
    'subject-processing-agreement-3' => array(
        'subtitle' => '',
        'content' => _x('The Processor guarantees the implementation of appropriate technical and organisational measures, so that the Processing complies with the requirements of the Regulation and the protection of the rights of the Data Subject is guaranteed.', 'Legal document processing agreement', 'complianz'),
    ),
    'subject-processing-agreement-4' => array(
        'subtitle' => '',
        'content' => _x('The Processor guarantees compliance with the requirements of applicable legislation and regulations relating to the processing of Personal Data. ', 'Legal document processing agreement', 'complianz'),
    ),
    'subject-processing-agreement-5' => array(
        'subtitle' => '',
        'content' => _x('The personal data to be processed on the instructions of the Controller shall remain the property of the Controller.', 'Legal document processing agreement', 'complianz'),
    ),

    'article-3' => array(
        'title' => _x('Entry into force and duration', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),
    'article-3-1' => array(
        'subtitle' => '',
        'content' => _x('This Agreement shall enter into force on the date it is signed by the Parties.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-3-2' => array(
        'subtitle' => '',
        'content' => _x('This Data Processing Agreement shall terminate after and insofar as the Processor has deleted or returned all Personal Data in accordance with Article 10.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-3-3' => array(
        'subtitle' => '',
        'content' => _x('Neither Party may terminate this Data Processing Agreement prematurely.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-3-4' => array(
        'subtitle' => '',
        'content' => _x('Parties may only amend this Agreement by mutual consent.', 'Legal document processing agreement', 'complianz'),
    ),

    'article-4' => array(

        'title' => _x('Scope of Processing Authority of the Processor', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),
    'article-4.1' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall process the Personal Data exclusively on the basis of written instructions from the Controller, except in the case of derogating statutory provisions applicable to the Processor.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-4.2' => array(
        'content' => _x('If, in the opinion of the Processor, an instruction as referred to in the first paragraph conflicts with a statutory regulation on data protection, it shall inform the Controller thereof prior to the Processing, unless a statutory regulation prohibits such notification.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-4.3' => array(
        'subtitle' => '',
        'content' => _x('If the Processor is required to provide Personal Data on the basis of a statutory provision, it shall inform the Controller without delay and, if possible, prior to providing the data.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-4.4' => array(
        'subtitle' => '',
        'content' => _x('The Processor has no control over the purpose and means of Processing of Personal Data.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-5' => array(
        'title' => _x('Security of the Processing', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),
    'article-5-1' => array(
        'subtitle' => '',
        'content' => _x('The Processor will endeavour to implement adequate technical and organisational measures with regard to the processing operations of personal data to be carried out, against loss or any form of unlawful processing (such as unauthorised disclosure, deterioration, alteration or transmission of personal data).', 'Legal document processing agreement', 'complianz'),
        'condition' => array(
            'security_measures' => 1,
        )
    ),
    'article-5-2' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall endeavour to implement adequate technical and organisational measures with respect to the processing operations of personal data to be carried out, against loss or any form of unlawful processing (such as unauthorised disclosure, deterioration, alteration or transmission of personal data). To this end, the Processor shall take the technical and organisational security measures as set out in a separate security protocol.', 'Legal document processing agreement', 'complianz'),
        'condition' => array(
            'security_measures' => 2,
        )
    ),
    'article-5-3' => array(
        'subtitle' => '',
        'content' => _x('The security protocol shall be added to this Agreement as a separate annex and shall be available from the Processor upon request.', 'Legal document processing agreement', 'complianz'),
        'condition' => array(
            'security-protocol-where' => 1,
        )
    ),
    'article-5-4' => array(
        'subtitle' => '',
        'content' => sprintf(_x('The security protocol can be viewed online %shere%s.', 'Legal document processing agreement', 'complianz'), '[security-protocol-where-url]', '[/security-protocol-where-url]'),
        'condition' => array(
            'security-protocol-where' => 2,
        )
    ),
    'article-5-5' => array(
        'subtitle' => '',
        'content' => sprintf(_x('The Processor will endeavour to implement adequate technical and organisational measures with respect to the processing operations of personal data to be carried out, against loss or any form of unlawful processing (such as unauthorised disclosure, deterioration, alteration or transmission of personal data). To this end, the Processor shall take the technical and organisational security measures as set out in %s.', 'Legal document processing agreement', 'complianz'), '[annex-security-measures]'),
        'condition' => array(
            'security_measures' => 3,
        )
    ),
    'article-5-6' => array(
        'subtitle' => '',
        'content' => _x('Parties recognise that ensuring an appropriate level of security may require additional security measures to be implemented at any time. The Processor shall ensure a level of security appropriate to the risk. If and insofar as the Controller explicitly requests this in writing, the Processor shall implement additional measures with respect to the security of the Personal Data.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-5-7' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall not process Personal Data outside the European Union, unless explicit written consent to do so has been granted by the Controller and subject to derogating statutory obligations.', 'Legal document processing agreement', 'complianz'),
        'condition' => array(
            'allow-outside-eu' => 1,
        )
    ),

    'article-5-8' => array(
        'content' => _x('The Processor may process the personal data in countries within the European Union. Transfer to countries outside the EU is permitted, provided the relevant legal conditions have been met. Upon request, the Processor shall inform the Controller of the country or countries that is or are involved.', 'Legal document processing agreement', 'complianz'),
        'condition' => array(
            'allow-outside-eu' => 2,
        )
    ),
    'article-5-9' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall inform the Controller without unreasonable delay as soon as it has become aware of any unlawful Processing of Personal Data or any breach of security measures as referred to in the first and second paragraph.', 'Legal document processing agreement','complianz'),
    ),

    'article-5-10' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall assist the Controller in compliance with the obligations under Articles 32 through 36 of the Regulation.', 'Legal document processing agreement', 'complianz'),
    ),

    'article-6' => array(
        'title' => _x('Duty of Confidentiality of Personnel of the Processor', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('', 'Legal document processing agreement', 'complianz'),
    ),
    'article-6-1' => array(
        'subtitle' => '',
        'content' => _x('The Personal Data is of a confidential nature. The Processor shall not use this data for any purpose other than for which it has been acquired, even if it has been converted into such a form that it cannot be traced to data subjects.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-6-2' => array(
        'subtitle' => '',
        'content' => _x('At the request of the Controller, the Processor shall demonstrate that its Personnel have undertaken to observe confidentiality. The personal data will only be disclosed to those employees and/or third parties who must necessarily take cognisance of the Personal Data.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-6-3' => array(
        'subtitle' => '',
        'content' => _x('This duty of confidentiality shall not apply where the Controller has given express consent to disclose the data to third parties, if disclosure of the data to third parties is logically necessary given the nature of the assignment and the performance of this Data Processing Agreement, or if there is a statutory obligation to disclose the data to a third party.', 'Legal document processing agreement', 'complianz'),
    ),

    'article-7' => array(
        'title' => _x('Sub-processor', 'Legal document processing agreement', 'complianz'),
        'content' => _x('', 'Legal document processing agreement', 'complianz'),
    ),
    'article-7-1' => array(
        'subtitle' => '',
        'content' => _x('Within the scope of the Agreement, the Processor may make use of third parties on condition that the Controller is informed thereof in advance; the Controller may terminate the Agreement if it cannot accept the use of a specific third party.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-7-2' => array(
        'subtitle' => '',
        'content' => _x('In any case, the Processor shall ensure that these third parties assume, in writing, at least the same obligations as those agreed between the Controller and the Processor.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-7-3' => array(
        'subtitle' => '',
        'content' => _x('The Processor is responsible for correct compliance with the obligations under this Data Processing Agreement by these third parties, and in the event of errors by these third parties it shall be liable as if it were at fault.', 'Legal document processing agreement', 'complianz'),
    ),

    'article-8' => array(
        'subtitle' => '',
        'title' => _x('Assistance on account of the rights of the Data Subject', 'Legal document processing agreement', 'complianz'),
    ),
    'article-8-1' => array(
        'subtitle' => '',
        'content' => _x('In the event a data subject submits a request to the Processor to exercise his/her legal rights, the Processor shall forward the request to the Controller, and the Controller shall further handle the request. The Processor may inform the data subject accordingly. ', 'Legal document processing agreement', 'complianz'),
    ),
    'article-8-2' => array(
        'subtitle' => '',
        'content' => _x("The Processor shall, to the extent within its power, provide assistance to the Controller in fulfilling the latter's obligation to respond to requests of the Data Subject to exercise its rights laid down in Chapter III of the Regulation. ", 'Legal document processing agreement', 'complianz'),
    ),
    'article-8-3' => array(
        'subtitle' => '',
        'content' => _x('The Processor may charge the additional costs it incurs in this respect to the Controller.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('deal_with_requests' => 2),
    ),

    'article-9' => array(
        'title' => _x('Personal Data Breach', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),
    'article-9-1a' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall inform the Controller without unreasonable delay, as soon as it has become aware of a Personal Data Breach. ', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-informed' => 1),
    ),
    'article-9-1b' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall inform the Controller without unreasonable delay, as soon as it has become aware of a Personal Data Breach, but no later than within 24 hours after discovery.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-informed' => 2),
    ),
    'article-9-1c' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall inform the Controller without unreasonable delay, as soon as it has become aware of a Personal Data Breach, but no later than within 36 hours after discovery.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-informed' => 3),
    ),
    'article-9-2' => array(
        'subtitle' => '',
        'content' => _x('Information that must at least be provided by the Processor shall include:', 'Legal document processing agreement', 'complianz') .
            '<ul>
                        <li>' . _x('The nature of the Personal Data Breach', 'complianz') . '</li>
                          <li>' . _x('The Personal Data and Data Subject', 'complianz') . '</li>
                        <li>' . _x('Likely consequences of the Personal Data Breach', 'Legal document processing agreement', 'complianz') . '</li>
                        <li>' . _x('Measures proposed or implemented by the Processor to address the Personal Data Breach, including, where appropriate, measures to mitigate its possible adverse effects.', 'Legal document processing agreement', 'complianz') . '</li>
                    </ul>',
    ),
    'article-9-3' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall also inform the Controller of further developments concerning the Personal Data Breach after having reported the breach pursuant to the first paragraph. ', 'Legal document processing agreement', 'complianz'),
    ),
    'article-9-4' => array(
        'subtitle' => '',
        'content' => _x('Each party shall bear their own costs relating to the report to the competent supervisory authority and the Data Subject. ', 'Legal document processing agreement', 'complianz'),
    ),
    'article-9-5' => array(
        'subtitle' => '',
        'content' => _x('In accordance with Article 33, paragraph 5 of the GDPR, the Processor shall document all data breaches, including the facts relating to the Personal Data Breach, its consequences and the corrective measures taken. Upon re-quest, the Processor shall provide the Controller with access to this information.', 'Legal document processing agreement', 'complianz'),
    ),

    'article-10' => array(

        'title' => _x('Returning Personal Data', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),

    'article-10-1' => array(
        'subtitle' => '',
        'content' => _x('After expiry of the Agreement, the Processor shall, at the discretion of the Controller, arrange for the return of all Personal Data to the Controller or for the erasure of all Personal Data. The Processor shall remove all copies, except where otherwise provided by law.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-11' => array(
        'title' => _x('Obligation to disclose information and audit', 'Legal document processing agreement', 'complianz'),
    ),
    'article-11-1' => array(
        'subtitle' => '',
        'content' => _x('The Controller shall have the right to conduct audits to verify compliance with all points of the Data Processing Agreement and everything directly related to this. This audit shall only take place after the Controller has requested similar audit reports from the Processor, reviewed them, and put forward reasonable arguments to justify an audit initi-ated by the Controller. ', 'Legal document processing agreement', 'complianz'),
        'condition' => array('audit' => 2),
    ),
    'article-11-2' => array(
        'subtitle' => '',
        'content' => __('The Controller shall have the right to have audits carried out by an independent external ex-pert, who is bound by confidentiality, to verify compliance with all points of the Data Pro-cessing Agreement and everything directly related to this. This audit shall only take place after the Controller has requested similar audit reports from the Processor, reviewed them, and put forward reasonable arguments to justify an audit initi-ated by the Controller. ', 'complianz'),
        'condition' => array('audit' => 1),
    ),
    'article-11-3' => array(
        'subtitle' => '',
        'content' => _x('Such an audit is justified if the similar audit reports present at the Processor\'s are inconclu-sive or insufficiently conclusive with respect to the Processor\'s compliance with this Data Pro-cessing Agreement. The Controller shall communicate the audit to the Processor in advance, with due observance of a minimum period of two weeks. ', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-audit' => 1),
    ),
    'article-11-4' => array(
        'subtitle' => '',
        'content' => _x('Such an audit shall be justified in the event of a concrete suspicion of abuse. The Controller shall communicate the audit to the Processor in advance, with due observance of a minimum period of two weeks. ', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-audit' => 2),
    ),
    'article-11-5' => array(
        'subtitle' => '',
        'content' => _x('Such an audit may be carried out once every three months, and more often in the event of a concrete suspicion of abuse. The Controller shall communicate the audit to the Processor in advance, with due observance of a minimum period of two weeks', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-audit' => 3),
    ),
    'article-11-6' => array(
        'subtitle' => '',
        'content' => __('Such an audit may be carried out once every calendar year, and more often in the event of a concrete suspicion of abuse. The Controller shall communicate the audit to the Processor in advance, with due observance of a minimum period of two weeks. ', 'Legal document processing agreement', 'complianz'),
        'condition' => array('when-audit' => 4),
    ),
    'article-11-7' => array(
        'subtitle' => '',
        'content' => _x('The findings in respect of the audit carried out shall be implemented by the Processor as soon as possible.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('what-do-with-findings' => 1),
    ),
    'article-11-8' => array(
        'subtitle' => '',
        'content' => _x('The findings of the audit carried out will be assessed by the Parties in joint consultation and, depending on the assessment, implemented (or not) by either Party or jointly by both Parties.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('what-do-with-findings' => 2),
    ),
    'article-11-9' => array(
        'subtitle' => '',
        'content' => _x('The costs of the audit as described in paragraph 1 shall be borne by the Data Controller. ', 'Legal document processing agreement', 'complianz'),
        'condition' => array('audit-costs' => 1),
    ),
    'article-11-10' => array(
        'subtitle' => '',
        'content' => _x('The costs of the audit as described in paragraph 1 shall be borne by the Processor', 'Legal document processing agreement', 'complianz'),
        'condition' => array('audit-costs' => 2),
    ),
    'article-11-11' => array(
        'subtitle' => '',
        'content' => _x('The costs of the audit as described in paragraph 1 shall be borne by the Processor, in the event of non-trivial breaches of the obligations arising from the Data Processing Agreement. Otherwise, the costs shall be borne by the Controller.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('audit-costs' => 3),
    ),


    'article-12' => array(
        'title' => _x('Other Terms and Conditions', 'Legal document processing agreement', 'complianz'),
    ),

    'article-12-1' => array(
        'subtitle' => '',
        'content' => _x('The Processor shall be liable towards the Controller for all consequences of the breach of this Data Processing Agreement, and shall indemnify the Controller against all claims by third parties, including any penalties, to the extent attributable to the Processor.', 'Legal document processing agreement', 'complianz'),
    ),
    'article-12-2' => array(
        'subtitle' => '',
        'content' => sprintf(_x('The liability of the Processor shall never exceed %s per year.', 'Legal document processing agreement', 'complianz'), '[amount-liable]').
                    '&nbsp;'._x('The limitation referred to in this Article shall not apply if and insofar as the damage is the result of intent or deliberate recklessness on the part of the Service Provider or its management.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('maximize-liability' => 'yes'),
    ),

//    The liability of the Service Provider shall never exceed [line 144 input field] per year.
//The limitation referred to in this Article shall not apply if and insofar as the damage is the result of intent or deliberate recklessness on the part of the Service Provider or its management."
    'article-12-2b' => array(
        'subtitle' => '',
        'content' => sprintf(_x('During the Data Processing Agreement, the Processor shall have and continue to have adequate insurance cover in place for liability in accordance with this article. The insurance policy should at least cover %s', 'Legal document processing agreement', 'complianz'), '[max_cost_of_insurance]'),
        'condition' => array('insurance' => 'yes'),
    ),

    'article-12-3' => array(
        'subtitle' => '',
        'content' => _x('The limitation referred to in this Article shall not apply if and insofar as the damage is the result of intent or deliberate recklessness on the part of the Processor or its management.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('insurance' => 'yes')
    ),

    'article-12-5' => array(
        'subtitle' => '',
        'content' => _x('The insurance shall cover:', 'Legal document processing agreement', 'complianz').
                        '[insurance_conditions]',
    ),
    'article-12-6' => array(
        'subtitle' => '',
        'content' => _x('The insurance conditions may be viewed upon request.', 'Legal document processing agreement', 'complianz'),
        'condition' => array('access-to-policy' => 'yes')
    ),
    'annex' => array(
        'annex' => true,
        'title' => _x('The Processing of Personal Data', 'Legal document processing agreement:paragraph title', 'complianz'),
    ),

    'annex-1' => array(
        'numbering' => false,
        'subtitle' => _x('Purpose of the processing', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => '[processor-activities]',
    ),

    'annex-2' => array(
        'numbering' => false,
        'subtitle' => _x('Personal Data', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => sprintf(_x('Within the scope of the Data Processing Agreement, the Processor shall process the following (special) personal data on the instructions of the Controller:<br>%s<br>%s', 'Legal document processing agreement', 'complianz'), '[what-kind-of-data]', '[what-kind-of-data-other]'),
    ),

    'annex-3' => array(
        'numbering' => false,
        'subtitle' => _x('Data subject categories', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => sprintf(_x('Personal data of the following groups of persons shall be processed:<br>%s<br>%s', 'Legal document processing agreement', 'complianz'), '[data-from-whom]', '[data-from-whom-other]'),
    ),

    'annex-4' => array(
        'numbering' => false,
        'subtitle' => _x('Data subject categories', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('The Controller shall ensure that the purposes, personal data, and categories of data subjects described in this Annex 1 are complete and correct, and shall indemnify the Processor against any defects and claims resulting from an incorrect representation by the Controller.', 'Legal document processing agreement', 'complianz'),
    ),

    'security-measures' => array(
        'annex' => true,
        'title' => _x('Security measures ', 'Legal document processing agreement:paragraph title', 'complianz'),
        'content' => _x('The purpose of this annex is to further specify the standards and measures the Processor must apply in connection with the security of the Processing. The following security measures have been taken:', 'Legal document processing agreement', 'complianz') .
            '[processing-security-measures]<br>[processing-security-measures-other]',
        'condition' => array('security_measures' => 3)
    ),

    'annex-6-thirdparty' => array(
        'annex' => true,
        'title' => _x('Engagement of third parties and/or sub-processors', 'Legal document processing agreemen:paragraph title', 'complianz'),
        'content' => _x('The Controller has given the Processor permission to engage the following third parties and/or sub-processor(s):', 'Legal document processing agreement', 'complianz'),
    ),

    'sign' => array(
        'content' => '<br><br><br><br><br><br><br><div style="width:100%"><div style="float:left;border-bottom:1px solid black;width:20%"></div><div style="float:right;border-bottom:1px solid black;width:20%"></div></div>',
    ),

);
