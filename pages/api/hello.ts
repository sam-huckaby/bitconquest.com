// Next.js API route support: https://nextjs.org/docs/api-routes/introduction
import type { NextApiRequest, NextApiResponse } from 'next';

const sgMail = require('@sendgrid/mail');
sgMail.setApiKey(process.env.SENDGRID_API_KEY);

export default async (
  req: NextApiRequest,
  res: NextApiResponse
) => {
    // using Twilio SendGrid's v3 Node.js Library
    // https://github.com/sendgrid/sendgrid-nodejs
    const msg = {
        to: process.env.CONTACT_EMAIL,
        from: 'hello@samhuckaby.com',
        subject: 'New Message From BitConquest.com Landing Page',
        text: req.body.message,
        html: '<p><strong>Message:</strong></p><p>' + req.body.message + '</p>',
    };

    try {
      await sgMail.send(msg);

      res.status(200).json({ success: true });
    } catch (e:any) {
      console.log(e);
    }
}