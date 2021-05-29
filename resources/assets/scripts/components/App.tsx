import React, { useState } from 'react';

export default function App() {
  const [count, setCount] = useState(0);
  return (
    <div>
      <b>I'm a React App!</b>
      <br />
      <button
        onClick={() => {
          setCount(count + 1);
        }}
      >
        {count} clicks
      </button>
    </div>
  );
}
