import React, { useState } from 'react';
import Head from 'next/head';

export default function Home() {
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(false);
  const [sent, setSent] = useState(false);

  async function sendMessage() {
    setLoading(true);

    try {
      await fetch("/api/hello", {
        "method": "POST",
        "headers": { "content-type": "application/json" },
        "body": JSON.stringify({message})
      });

      // Give the user some assurance that things work as expected
      setTimeout(() => {
        setLoading(false);
            setSent(true);
      }, 1000);

      setTimeout(() => {
        // Clear out the message box
        setMessage('');

        // Remove the sent veil
        setSent(false);
      }, 3000);
    } catch (error) {
        // Just in case there was a problem...
        setLoading(false);
    }
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-screen py-2 bg-neutral-100 text-neutral-700 dark:bg-neutral-700 dark:text-neutral-100">
      <Head>
        <title>BitConquest.com - In-Development</title>
        <link rel="icon" href="/favicon.ico" />
      </Head>

      <main className="flex flex-col items-center justify-center w-full flex-1 px-20 text-center overflow-hidden">
        <h1 className="text-4xl font-bold">
          BitConquest.com
        </h1>

        <p className="mt-5 text-2xl">
          This site is currently in-development by
        </p>
        <p className="text-2xl">
          <a className="underline" href="https://samhuckaby.com/" target="_blank">Sam Huckaby</a>
        </p>

        <div className="flex flex-col justify-center items-center mt-10 w-screen p-2 relative">
          <div className="text-xl mt-2">Questions or Comments?</div>
          <textarea rows={5} value={message} onChange={(e) => setMessage(e.target.value)} className="w-full md:w-5/12 bg-transparent mt-2 rounded border-solid border border-neutral-700 dark:border-neutral-100"></textarea>
          <button onClick={sendMessage} className="bg-green-500 dark:bg-green-700 rounded text-neutral-700 dark:text-neutral-100 mt-2 p-2">Message Me</button>
          <div className={((loading)? 'absolute' : 'hidden') + ` top-0 right-0 bottom-0 left-0 flex flex-col justify-center items-center text-3xl bg-stone-500/50 text-white`}>
            <div className="inline border-8 h-10 w-10 border-t-black border-r-white border-b-white border-l-white border-solid rounded-full animate-spin">&nbsp;</div>
          </div>
          <div className={((sent)? 'absolute' : 'hidden') + ` top-0 right-0 bottom-0 left-0 flex flex-col justify-center items-center text-3xl bg-stone-500/50 text-white`}>
            Sent!
          </div>
        </div>
      </main>

      <footer className="flex flex-col items-center justify-center w-full h-24 border-t">
        <a
          className="flex items-center justify-center"
          href="https://vercel.com?utm_source=create-next-app&utm_medium=default-template&utm_campaign=create-next-app"
          target="_blank"
          rel="noopener noreferrer"
        >
          Powered by{' '}
          <img src="/vercel.svg" alt="Vercel Logo" className="h-4 ml-2" />
        </a>
        <div className="samhuckaby-container">
          Built by <a href="https://samhuckaby.com/" target="_blank" className="text-orange-600">Sam Huckaby</a>
        </div>
      </footer>
    </div>
  );
}
