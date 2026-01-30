import { useEffect, useState } from 'react';

type Props = {
  texts: string[];
  typingSpeed: number;
  pauseDuration: number;
  deletingSpeed: number;
  showCursor: boolean;
  cursorCharacter: string;
};

export default function TextType({
  texts,
  typingSpeed,
  pauseDuration,
  deletingSpeed,
  showCursor,
  cursorCharacter
}: Props) {
  const [displayed, setDisplayed] = useState('');
  const [textIndex, setTextIndex] = useState(0);
  const [charIndex, setCharIndex] = useState(0);
  const [deleting, setDeleting] = useState(false);

  useEffect(() => {
    if (!texts.length) return;
    let timeout: NodeJS.Timeout;
    const current = texts[textIndex];

    if (!deleting && charIndex < current.length) {
      timeout = setTimeout(() => {
        setDisplayed(current.slice(0, charIndex + 1));
        setCharIndex(charIndex + 1);
      }, typingSpeed);
    } else if (!deleting && charIndex === current.length) {
      timeout = setTimeout(() => setDeleting(true), pauseDuration);
    } else if (deleting && charIndex > 0) {
      timeout = setTimeout(() => {
        setDisplayed(current.slice(0, charIndex - 1));
        setCharIndex(charIndex - 1);
      }, deletingSpeed);
    } else if (deleting && charIndex === 0) {
      timeout = setTimeout(() => {
        setDeleting(false);
        setTextIndex((textIndex + 1) % texts.length);
      }, typingSpeed);
    }
    return () => clearTimeout(timeout);
  }, [texts, textIndex, charIndex, deleting, typingSpeed, pauseDuration, deletingSpeed]);

  return (
    <span>
      {displayed}
      {showCursor && <span className="text-type-cursor">{cursorCharacter}</span>}
    </span>
  );
}
