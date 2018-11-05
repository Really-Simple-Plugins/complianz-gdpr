<?php
defined('ABSPATH') or die("you do not have acces to this page!");
$this->document_elements['processing-us'] = array(
    array(
        'subtitle' => 'The undersigned:',
        'content' => '1. [organisation_name]',
    ),
    array(
        'content' => 'hereinafter referred to as: Controller',
    ),
    array(
        'content' => '<b>and</b>',
    ),
    array(
        'content' => '2. [name_of_processor-us]',
    ),
    array(
        'content' => 'hereinafter referred to as: Processor',
    ),
    array(
        'content' => 'hereinafter jointly referred to as: Parties; ',
    ),
    array(
        'subtitle' => 'WHEREAS:',
        'content' =>
            '<ul>
                        <li>Insofar as the Contractor processes Personal Data on behalf of the Client within the scope of the Agreement, the Client qualifies as the Controller for the Processing of Personal Data and the Contractor as the Service Provider;</li>
                        <li>The Contractor is agreeable to providing services to the Client on the terms and conditions set out in this Agreement</li>
                    </ul>',
    ),
    array(
        'subtitle' => 'IN CONSIDERATION OF',
        'content' => 'the matters described above and of the mutual benefits and obligations set forth in this Agreement, the receipt and sufficiency of which consideration is hereby acknowledged, the Client and the Contractor (individually the "Party" and collectively the "Parties" to this Agreement) agree as follows:',
    ),
    array(
        'title' => 'Definitions',
        'content' => 'The following terms used in this Data Processing Agreement shall have the meaning hereby assigned to them:',
    ),
    array(
        'subtitle' => 'Agreement',
        'content' => 'The agreement between the Controller and the Service Provider.'
    ),
    array(
        'subtitle' => 'Data Processing Agreement',
        'content' => 'This agreement including its recitals and annexes.',
    ),
    array(
        'subtitle' => 'Data Subject',
        'content' => 'The person to whom Personal Data relates.',
    ),
    array(
        'subtitle' => 'Personal Data',
        'content' => 'Any personal information relating to an identified or identifiable natural person that the Service Provider processes on behalf of the Controller within the scope of the Agreement.',
    ),
    array(
        'subtitle' => 'Processing',
        'content' => 'Any operation or any set of operations relating to Personal Data within the scope of the Agreement, carried out by means of automated processes or otherwise, such as collection, recording, organisation, structuring, storage, adaptation or alteration, retrieval, consultation, use, disclosure by means of transmission, disseminating or otherwise making available, aligning or combining, restriction, erasure or destruction.',
    ),
    array(
        'subtitle' => 'Regulation',
        'content' => 'The major privacy protection laws at the State and federal level.',
    ),
    array(
        'subtitle' => 'Security Breach',
        'content' => 'a breach of security that accidentally or unlawfully results in the destruction, loss, alteration or unauthorised disclosure or access to unencrypted personal data transmitted, stored or otherwise processed. This also includes encrypted personal information if the encryption key or security credential was, or is reasonably believed to have been, acquired by an unauthorized person and the person or business that owns or licenses the encrypted information has a reasonable belief that the encryption key or security credential could render that personal information readable or useable.',
    ),

    array(
        'title' => 'Subject of this Data Processing Agreement ',
    ),
    array(
        'subtitle' => '',
        'content' => 'This Data Processing Agreement regulates the Processing of Personal Data by the Service Provider within the scope of the Agreement.',
    ),
    array(
        'subtitle' => '',
        'content' => 'The nature and the purpose of the Processing, the type of Personal Data, and the categories of Data Subjects are set out in Annex 1. ',
    ),

    array(
        'title' => 'Entry into force and duration',
    ),
    array(
        'subtitle' => '',
        'content' => 'This Agreement shall enter into force on the date it is signed by the Parties.',
    ),
    array(
        'subtitle' => '',
        'content' => 'This Data Processing Agreement shall terminate after and insofar as the Processor has deleted or returned all Personal Data in accordance with Article 9.',
    ),
    array(
        'subtitle' => '',
        'content' => 'Neither Party may terminate this Data Processing Agreement prematurely.',
    ),
    array(
        'subtitle' => '',
        'content' => 'Parties may only amend this Agreement by mutual consent. Any amendment or modification of this Agreement or additional obligation assumed by either Party in connection with this Agreement will only be binding if evidenced in writing signed by each Party or an authorized representative of each Party.',
    ),

    array(

        'title' => 'Scope of Processing Authority of the Service Provider',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider shall process the Personal Data exclusively on the basis of written instructions from the Controller, except in the case of derogating statutory provisions applicable to the Service Provider.',
    ),
    array(
        'content' => 'If, in the opinion of the Service Provider, an instruction as referred to in the first paragraph conflicts with a statutory regulation on data protection, it shall inform the Controller thereof prior to the Processing, unless a statutory regulation prohibits such notification.',
    ),
    array(
        'subtitle' => '',
        'content' => 'If the Service Provider is required to provide Personal Data on the basis of a statutory provision, it shall inform the Controller without delay and, if possible, prior to providing the data.',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider is not allowed to do one of the following:
                    <ol class="alphabetic">
                        <li>Selling the Personal Data.</li>
                        <li>Retaining, using, or disclosing the Personal Data for any purpose other than for the specific purpose of performing the services specified in the contract, including retaining, using, or disclosing the Personal Data for a commercial purpose other than providing the services specified in the contract.</li>
                        <li>Retaining, using, or disclosing the information outside of the direct business relationship between the Service Provider and the Controller.</li>
                    </ol>',
    ),
    array(
        'title' => 'Security of the Processing',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider will endeavour to implement and maintain reasonable security procedures and practices appropriate to the nature of the information, to protect the Personal Information from unauthorised access, destruction, use, modification, or disclosure.',
        'condition' => array(
            'security_measures-us' => 1,
        )
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider will endeavour to implement and maintain reasonable security procedures and practices appropriate to the nature of the information, to protect the Personal Information from unauthorised access, destruction, use, modification, or disclosure. To this end, the Service Provider will take the technical and organisational security measures as set out in a separate security protocol.',
        'condition' => array(
            'security_measures-us' => 2,
        )
    ),
    array(
        'subtitle' => '',
        'content' => 'The security protocol will be added to this Agreement as a separate annex and shall be available from the Service Provider upon request.',
        'condition' => array(
            'security-protocol-where-us' => 1,
        )
    ),

    array(
        'subtitle' => '',
        'content' => sprintf('The security protocol can be viewed online %shere%s.', '[security-protocol-where-url-us]', '[/security-protocol-where-url-us]'),
        'condition' => array(
            'security-protocol-where-us' => 2,
        )
    ),

    array(
        'subtitle' => '',
        'content' => sprintf('The Service Provider will endeavour to implement and maintain reasonable security procedures and practices appropriate to the nature of the information, to protect the Personal Information from unauthorised access, destruction, use, modification, or disclosure. To this end, the Service Provider shall take the technical and organisational security measures as set out in %s.', '[annex-security-measures-us]'),
        'condition' => array(
            'security_measures' => 3,
        )
    ),

    array(
        'subtitle' => '',
        'content' => 'Parties recognise that ensuring an appropriate level of security may require additional security measures to be implemented at any time. The Service Provider shall ensure a level of security appropriate to the risk. If and insofar as the Controller explicitly requests this in writing, the Service Provider shall implement additional measures with respect to the security of the Personal Data. ',
    ),

    'article-5-7' => array(
        'subtitle' => '',
        'content' => 'The Service Provider shall not process Personal Data outside the United States of America, unless explicit written consent to do so has been granted by the Controller and subject to derogating statutory obligations.',
        'condition' => array(
            'allow-outside-us' => 1,
        )
    ),

    'article-5-8' => array(
        'content' => 'The Service Provider may process the personal data in States within the United States of America. Transfer to countries outside the US is permitted, provided the relevant legal conditions have been met. Upon request, the Service Provider shall inform the Controller of the country or countries that is or are involved.',
        'condition' => array(
            'allow-outside-us' => 2,
        )
    ),

    'article-6' => array(
        'title' => 'Duty of Confidentiality of Personnel of the Service Provider',
        'content' => 'The Personal Data is of a confidential nature. The Service Provider is required to maintain the confidentiality of the information and is prohibited from disclosing or using the information other than to carry out the service that is subject of this Data Processing Agreement.',
    ),
    'article-6-2' => array(
        'subtitle' => '',
        'content' => 'At the request of the Controller, the Service Provider shall demonstrate that its Personnel have undertaken to observe confidentiality. The personal data will only be disclosed to those employees and/or third parties who must necessarily take cognisance of the Personal Data.',
    ),
    'article-6-3' => array(
        'subtitle' => '',
        'content' => 'This duty of confidentiality shall not apply where the Controller has given express consent to disclose the data to third parties, if disclosure of the data to third parties is logically necessary given the nature of the assignment and the performance of this Data Processing Agreement, or if there is a statutory obligation to disclose the data to a third party.',
    ),

    array(
        'title' => 'Assistance on account of the rights of the Data Subject',
        'content' => 'In the event a data subject submits a request to the Service Provider to exercise his/her legal rights, the Service Provider shall forward the request to the Controller, and the Controller shall further handle the request. The Service Provider may inform the data subject accordingly.',
    ),
    array(
        'content' => "The Service Provider shall, to the extent within its power, provide reasonable assistance to the Controller in fulfilling the latter's obligation to respond to requests of the Data Subject to exercise its rights laid down in the Regulation.",
    ),
    array(
        'content' => 'The Service Provider may charge the reasonable additional costs it incurs in this respect to the Controller.',
        'condition' => array('deal_with_requests-us' => 2),
    ),

    array(
        'title' => 'Security Breach',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider shall inform the Controller without unreasonable delay, as soon as it has become aware of a Security Breach.',
        'condition' => array('when-informed-us' => 1),
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider shall inform the Controller without unreasonable delay, as soon as it has become aware of a Security Breach, but no later than within 24 hours after discovery.',
        'condition' => array('when-informed-us' => 2),
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider shall inform the Controller without unreasonable delay, as soon as it has become aware of a Security Breach, but no later than within 36 hours after discovery.',
        'condition' => array('when-informed-us' => 3),
    ),
    array(
        'subtitle' => '',
        'content' => 'Information that must at least be provided by the Service Provider shall include:' .
            '<ul>
                        <li>The nature of the Personal Data Breach</li>
                        <li>The Personal Data and Data Subject</li>
                        <li>Likely consequences of the Security Breach</li>
                        <li>Measures proposed or implemented by the Service Provider to address the Security Breach, including, where appropriate, measures to mitigate its possible adverse effects.</li>
                    </ul>',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider shall also inform the Controller of further developments concerning the Security Breach after having reported the breach pursuant to the first paragraph.',
    ),
    array(
        'subtitle' => '',
        'content' => 'Each party shall bear their own costs relating to the report to the Data Subject.',
    ),

    array(
        'title' => 'Returning Personal Data',
    ),
    array(
        'subtitle' => '',
        'content' => 'After expiry of the Agreement, the Service Provider shall, at the discretion of the Controller, arrange for the return of all Personal Data to the Controller or for the erasure of all Personal Data. The Service Provider shall remove all copies, except where otherwise provided by law. ',
    ),
    array(
        'title' => 'Obligation to disclose information',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Service Provider shall provide all information necessary to demonstrate that the obligations arising from this Data Processing Agreement have been and are being fulfilled.   ',
    ),
    array(
        'subtitle' => '',
        'content' => 'The Controller shall have the right to conduct audits to verify compliance with all points of the Data Processing Agreement and everything directly related to this. This audit shall only take place after the Controller has requested similar audit reports from the Service Provider, reviewed them, and put forward reasonable arguments to justify an au-dit initiated by the Controller. ',
        'condition' => array('audit-us' => 2),
    ),
    array(
        'subtitle' => '',
        'content' => 'The Controller shall have the right to have audits carried out by an independent external ex-pert, who is bound by confidentiality, to verify compliance with all points of the Data Pro-cessing Agreement and everything directly related to this. This audit shall only take place after the Controller has requested similar audit reports from the Service Provider, reviewed them, and put forward reasonable arguments to justify an au-dit initiated by the Controller. ',
        'condition' => array('audit-us' => 1),
    ),
    array(
        'subtitle' => '',
        'content' => "Such an audit is justified if the similar audit reports present at the Service Provider's are in-conclusive or insufficiently conclusive with respect to the Service Provider's compliance with this Data Processing Agreement. The Controller shall communicate the audit to the Service Provider in advance, with due observance of a minimum period of two weeks.",
        'condition' => array('when-audit-us' => 1),
    ),
    array(
        'subtitle' => '',
        'content' => 'Such an audit shall be justified in the event of a concrete suspicion of abuse. The Controller shall communicate the audit to the Service Provider in advance, with due observance of a minimum period of two weeks. ',
        'condition' => array('when-audit-us' => 2),
    ),
     array(
        'subtitle' => '',
        'content' => 'Such an audit may be carried out once every three months, and more often in the event of a concrete suspicion of abuse. The Controller shall communicate the audit to the Service Pro-vider in advance, with due observance of a minimum period of two weeks.',
        'condition' => array('when-audit-us' => 3),
    ),
    array(
        'subtitle' => '',
        'content' => 'Such an audit may be carried out once every calendar year, and more often in the event of a concrete suspicion of abuse. The Controller shall communicate the audit to the Service Pro-vider in advance, with due observance of a minimum period of two weeks. ',
        'condition' => array('when-audit-us' => 4),
    ),
    array(
        'subtitle' => '',
        'content' => 'The findings in respect of the audit carried out shall be implemented by the Service Provider as soon as possible.',
        'condition' => array('what-do-with-findings-us' => 1),
    ),
    array(
        'subtitle' => '',
        'content' => 'The findings of the audit carried out will be assessed by the Parties in joint consultation and, depending on the assessment, implemented (or not) by either Party or jointly by both Parties.',
        'condition' => array('what-do-with-findings-us' => 2),
    ),
    array(
        'subtitle' => '',
        'content' => 'The costs of the audit as described in paragraph 1 shall be borne by the Controller.',
        'condition' => array('audit-costs-us' => 1),
    ),
    array(
        'subtitle' => '',
        'content' => 'The costs of the audit as described in paragraph 1 shall be borne by the Service Provider. ',
        'condition' => array('audit-costs-us' => 2),
    ),
    array(
        'subtitle' => '',
        'content' => 'The costs of the audit as described in paragraph 1 shall be borne by the Service Provider, in the event of non-trivial breaches of the obligations arising from the Data Processing Agreement. Otherwise, the costs shall be borne by the Controller.',
        'condition' => array('audit-costs-us' => 3),
    ),


    array(
        'title' => 'Other Terms and Conditions',
    ),

    array(
        'subtitle' => '',
        'content' => 'The Processor shall be liable towards the Controller for all consequences of the breach of this Data Processing Agreement, and shall indemnify the Controller against all claims by third parties, including any penalties, to the extent attributable to the Processor.',
    ),
    array(
        'subtitle' => '',
        'content' => sprintf('The liability of the Service Provider shall never exceed %s per year. The limitation referred to in this Article shall not apply if and insofar as the damage is the result of intent or deliberate recklessness on the part of the Service Provider or its management.', '[amount-liable-us]'),
        'condition' => array('maximize-liability' => 'yes'),
    ),
    array(
        'subtitle' => '',
        'content' => sprintf(_x('During the Data Processing Agreement, the Service Provider shall have and continue to have adequate insurance cover in place for liability in accordance with this article. The insurance policy should at least cover %s', 'Legal document processing agreement', 'complianz'), '[max_cost_of_insurance-us]'),
        'condition' => array('insurance-us' => 'yes')
    ),

    array(
        'subtitle' => '',
        'content' => 'The insurance shall cover: [insurance_conditions-us]',
    ),
    array(
        'subtitle' => '',
        'content' => 'The insurance conditions may be viewed upon request.',
        'condition' => array('access-to-policy-us' => 'yes')
    ),

    array(
        'content' => 'In the event that any of the provisions of this Agreement are held to be invalid or unenforceable in whole or in part, all other provisions will nevertheless continue to be valid and enforceable with the invalid or unenforceable parts severed from the remainder of this Agreement.',
    ),

    /*signature*/
    array(
        'numbering' => false,
        'content' => 'IN WITNESS WHEREOF the Parties have duly affixed their signatures under hand and seal on this<br>
                      <br>
                      ___________ day of ____________<br>
                      <br>
                      <br>
                      <br>
                      Controller:<br>
                      <br>
                      Per:___________________________(Seal)<br>
                      <br>
                      <br>
                      <br>
                      Service Provider:<br>
                      <br>
                      Per:___________________________(Seal)<br><br>',
    ),

    array(
        'numbering' => false,
        'title' => 'Certification as required by Section 1798.40 CaCPA:',
        'content' => 'As the person receiving the personal information I hereby certify that I understand the following restrictions and will comply with them.<br>
                        This agreement prohibits me from:
                        <ol class="alphabetic">
                        <li>Selling the personal information.</li>
                        <li>Retaining, using, or disclosing the personal information for any purpose other than for the specific purpose of performing the services specified in the contract, including retaining, using, or disclosing the personal information for a commercial purpose other than providing the services specified in the contract.</li>
                        <li>Retaining, using, or disclosing the information outside of the direct business relationship between myself and the business.</li>
                        </ol>'
    ),

    /*signature*/
    array(
        'numbering' => false,
        'content' => 'Name<br>
                      <br>
                      ________________<br>
                      <br>
                      Date<br>
                      <br>
                      ________________<br>
                      Name Service Provider:<br>
                      <br>
                      <br>
                      Per:___________________________(Seal)<br><br>'
    ),

    array(
        'annex' => true,
        'title' => 'The Processing of Personal Data',
    ),

    array(
        'numbering' => false,
        'subtitle' => 'Purpose of the processing',
        'content' => '[processor-activities-us]',
    ),

    array(
        'numbering' => false,
        'subtitle' => 'Personal Data',
        'content' => sprintf('Within the scope of the Data Processing Agreement, the Service Provider shall process the following Personal data on the instructions of the Controller:<br>%s<br>%s', '[what-kind-of-data-us]', '[what-kind-of-data-other-us]'),
    ),

    array(
        'numbering' => false,
        'subtitle' => 'Data subject categories',
        'content' => sprintf('Personal data of the following groups of persons shall be processed:<br>%s<br>%s', '[data-from-whom-us]', '[data-from-whom-other-us]'),
    ),

    array(
        'numbering' => false,
        'subtitle' => 'Data subject categories',
        'content' => 'The Controller shall ensure that the purposes, personal data, and categories of data subjects described in this Annex 1 are complete and correct, and shall indemnify the Processor against any defects and claims resulting from an incorrect representation by the Controller.',
    ),

    //index used to refer to in agreement.
    'security-measures-us' => array(
        'annex' => true,
        'title' => 'Security measures ',
        'content' => 'The purpose of this annex is to further specify the standards and measures the Service Provider must apply in connection with the security of the Processing. The following security measures have been taken:' .
            '[processing-security-measures-us]<br>[processing-security-measures-other-us]',
        'condition' => array('security_measures-us' => 3)
    ),


);
